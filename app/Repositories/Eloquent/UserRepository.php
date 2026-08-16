<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function applyFilter(Builder $builder, array $filters = []): Builder
    {
        return $builder;
    }

    public function findByMobileAndCountryCode(string $countryCode, string $mobile): ?User
    {
        return User::where('mobile', $mobile)->where('mobile_country_code', $countryCode)->first();
    }
}
