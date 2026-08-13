<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repo {name} {--model=} {--admin} {--path=}';
    protected $description = 'Generate Repository + Interface + Service + optional Controller with custom path';

    private string $model = 'model';

    public function handle()
    {
        $repoName = Str::studly($this->argument('name'));

        $modelName = $this->option('model')
            ? Str::studly($this->option('model'))
            : $repoName;

        $this->model = $modelName;

        $modelClass = "App\\Models\\{$this->model}";
        $modelVariable = Str::camel($this->model);

        $isAdmin = $this->option('admin');
        $customPath = $this->option('path');

        $this->createFolders();

        $this->createInterface($repoName);
        $this->createRepository($repoName, $modelClass, $modelVariable);
        $this->createService($repoName);

        if ($isAdmin || $customPath) {
            $this->createController($repoName, $customPath, $isAdmin);
        }

        $this->info("Repository structure created successfully for {$repoName}");
    }

    private function createFolders()
    {
        $folders = [
            app_path('Repositories'),
            app_path('Repositories/Contracts'),
            app_path('Repositories/Eloquent'),
            app_path('Repositories/Services'),
        ];

        foreach ($folders as $folder) {
            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }
        }
    }

    private function createInterface($repoName)
    {
        $path = app_path("Repositories/Contracts/{$repoName}RepositoryInterface.php");

        if (File::exists($path)) {
            return;
        }

        $content = "<?php

namespace App\Repositories\Contracts;

interface {$repoName}RepositoryInterface extends BaseRepositoryInterface
{
}
";

        File::put($path, $content);
    }

    private function createRepository($repoName, $modelClass, $modelVariable)
    {
        $path = app_path("Repositories/Eloquent/{$repoName}Repository.php");

        if (File::exists($path)) {
            return;
        }

        $content = "<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\\{$repoName}RepositoryInterface;
use {$modelClass};
use Illuminate\Database\Eloquent\Builder;

class {$repoName}Repository extends BaseRepository implements {$repoName}RepositoryInterface
{
    public function __construct({$this->model} \${$modelVariable})
    {
        parent::__construct(\${$modelVariable});
    }

    public function applyFilter(Builder \$builder, array \$filters = []): Builder
    {
        return \$builder;
    }
}
";

        File::put($path, $content);
    }

    private function createService($repoName)
    {
        $path = app_path("Repositories/Services/{$repoName}Service.php");

        if (File::exists($path)) {
            return;
        }

        $content = "<?php

namespace App\Repositories\Services;

use App\Repositories\Contracts\\{$repoName}RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class {$repoName}Service
{
    public function __construct(
        private {$repoName}RepositoryInterface \$repository
    ) {}

    public function paginate(array \$filters = [], int \$perPage = 15): LengthAwarePaginator
    {
        return \$this->repository->paginate(\$filters, \$perPage);
    }

    public function find(int \$id)
    {
        return \$this->repository->find(\$id);
    }

    public function findOrFail(int \$id)
    {
        return \$this->repository->findOrFail(\$id);
    }

    public function create(array \$data)
    {
        return \$this->repository->create(\$data);
    }

    public function update(\$model, array \$data)
    {
        return \$this->repository->update(\$model, \$data);
    }

    public function delete(\$model): bool
    {
        return \$this->repository->delete(\$model);
    }
}
";

        File::put($path, $content);
    }

    private function createController($repoName, $customPath = null, $isAdmin = false)
    {
        if ($customPath) {
            $segments = explode('/', $customPath);

            $namespace = 'App\\Http\\Controllers\\' . implode('\\', $segments);
            $dir = app_path('Http/Controllers/' . implode('/', $segments));
        } else {
            $namespace = 'App\\Http\\Controllers\\Admin';
            $dir = app_path('Http/Controllers/Admin');
        }

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $path = $dir . "/{$repoName}Controller.php";

        if (File::exists($path)) {
            return;
        }

        $content = "<?php

namespace {$namespace};

use App\Http\Controllers\Controller;
use App\Repositories\Services\\{$repoName}Service;
use Illuminate\Http\Request;

class {$repoName}Controller extends Controller
{
    public function __construct(
        private {$repoName}Service \$service
    ) {}

    public function index(Request \$request)
    {
        return \$this->service->paginate(
            \$request->all(),
            config('app.items_per_page')
        );
    }
}
";

        File::put($path, $content);
    }
}
