<?php

namespace App\Repositories\Services;

use App\Enums\LoginByEnum;
use App\Http\Services\PhoneNumberService;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
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

    public function loginBySanctum(array $data)
    {
        $user = $this->findUserForLogin($data);

        if (! $this->validatePassword($data['password'], $user)) {
            return false;
        }

        return $this->createAuthToken($user);
    }

    private function findUserForLogin(array $data)
    {
        return match (config('auth.login_via', LoginByEnum::EMAIL->value)) {
            LoginByEnum::EMAIL->value => $this->repository->findByCol('email', $data['email']),
            LoginByEnum::MOBILE->value => $this->repository->findByMobileAndCountryCode($data['mobile_country_code'], $data['mobile']),
            default => $this->findUserByEmailOrMobile($data),
        };
    }

    private function findUserByEmailOrMobile(array $data)
    {
        if (! empty($data['email'])) {
            return $this->repository->findByCol('email', $data['email']);
        }

        return $this->repository->findByMobileAndCountryCode($data['mobile_country_code'], $data['mobile']);
    }

    private function validatePassword(string $password, $user): bool
    {
        if (! $user) {
            return false;
        }

        return Hash::check($password, $user->password);    }

    private function createAuthToken($user)
    {
        $user->token = $user->createToken('auth_token')->plainTextToken;

        return $user;
    }
}
