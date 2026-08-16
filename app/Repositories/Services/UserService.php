<?php

namespace App\Repositories\Services;

use App\Http\Services\PhoneNumberService;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $repository
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
        $this->prepareMobileData($data);
        return $this->repository->create($data);
    }

    private function prepareMobileData(array &$data): void
    {
        if (empty($data['mobile_country_code']) || empty($data['mobile'])) {
            $data['mobile_country_code'] = null;
            $data['mobile'] = null;

            return;
        }

        $checkMobile = PhoneNumberService::checkMobile(
            $data['mobile_country_code'],
            $data['mobile']
        );

        if (! $checkMobile['status']) {
            throw ValidationException::withMessages([
                'mobile' => $checkMobile['message'],
            ]);
        }

        $countryCode = str_replace('+', '', $data['mobile_country_code']);

        $mobile = str_replace(
            '+'.$countryCode,
            '',
            $checkMobile['number_format']
        );

        if ($this->repository->findByMobileAndCountryCode($countryCode, $mobile)) {
            throw ValidationException::withMessages([
                'mobile' => __('messages.Mobile number already exists'),
            ]);
        }

        $data['mobile_country_code'] = $countryCode;
        $data['mobile'] = $mobile;
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
