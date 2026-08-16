<?php

namespace App\Repositories\Services;

use App\Enums\VerificationTypeEnum;
use App\Otp\OtpData;
use App\Otp\OtpManager;
use App\Repositories\Contracts\VerificationCodeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VerificationCodeService
{
    public function __construct(
        private VerificationCodeRepositoryInterface $repository
    ) {}

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function findOrFail(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data)
    {
        $data = $this->prepareData($data);
   
        $this->repository->create($data);
        $this->send($data);
    }

    private function prepareData(array $data): array
    {
        if (config('otp.activation_via') === 'email') {
            $data['type'] = VerificationTypeEnum::EMAIL;
            $data['recipient'] = $data['email'];
        } else {
            $data['type'] = VerificationTypeEnum::MOBILE;
            $data['recipient'] = $data['mobile_country_code'].$data['mobile'];
        }

        $data['driver'] = config('otp.send_via');

        $data['code'] = OtpManager::generateOTP();
        $data['expires_at'] = OtpManager::expiresAt();

        return $data;
    }

    private function send(array $data): void
    {
        OtpManager::make($data['driver'])->send(
            OtpData::fromArray([
                'code' => $data['code'],
                'recipient' => $data['recipient'],
                'purpose' => $data['purpose']->value,
                'isSendRealOTP' => OtpManager::isSendRealOTP(),
            ])
        );
    }

    public function update($model, array $data)
    {
        return $this->repository->update($model, $data);
    }

    public function delete($model): bool
    {
        return $this->repository->delete($model);
    }
}
