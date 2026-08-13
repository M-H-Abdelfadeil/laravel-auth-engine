<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\VerificationCodeRepositoryInterface;
use App\Models\VerificationCode;
use Illuminate\Database\Eloquent\Builder;

class VerificationCodeRepository extends BaseRepository implements VerificationCodeRepositoryInterface
{
    public function __construct(VerificationCode $verificationCode)
    {
        parent::__construct($verificationCode);
    }

    public function applyFilter(Builder $builder, array $filters = []): Builder
    {
        return $builder;
    }
}
