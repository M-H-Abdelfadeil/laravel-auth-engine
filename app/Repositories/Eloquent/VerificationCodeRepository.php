<?php

namespace App\Repositories\Eloquent;

use App\Models\VerificationCode;
use App\Repositories\Contracts\VerificationCodeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class VerificationCodeRepository extends BaseRepository implements VerificationCodeRepositoryInterface
{
    public function __construct(VerificationCode $verificationCode)
    {
        parent::__construct($verificationCode);
    }

    public function findByUserAndPurpose(int $userId, string $purpose): ?VerificationCode
    {
        return $this->model->where('user_id', $userId)->where('purpose', $purpose)->first();
    }

    public function deleteByUserAndPurpose(int $userId, string $purpose): bool
    {
        return $this->model->where('user_id', $userId)->where('purpose', $purpose)->delete();
    }

    public function applyFilter(Builder $builder, array $filters = []): Builder
    {
        return $builder;
    }
}
