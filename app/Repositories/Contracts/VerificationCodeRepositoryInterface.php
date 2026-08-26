<?php

namespace App\Repositories\Contracts;

use App\Models\VerificationCode;

interface VerificationCodeRepositoryInterface extends BaseRepositoryInterface
{
    public function deleteByUserAndPurpose(int $userId, string $purpose): bool;

    public function findByUserAndPurpose(int $userId, string $purpose): ?VerificationCode;
}
