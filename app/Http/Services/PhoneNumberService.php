<?php

namespace App\Http\Services;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberService
{
    /**
     * Validate a phone number using the provided country calling code.
     *
     * Resolves the country calling code to its ISO Alpha-2 country code,
     * validates the phone number using Google's libphonenumber library,
     * and returns the number in international format when valid.
     *
     * @param string $countryCode Country calling code, e.g. +20 or 20.
     * @param string $mobile Phone number to validate.
     *
     * @return array{
     *     status: bool,
     *     number_format?: string,
     *     message?: string
     * }
     */
    public static function checkMobile(string $countryCode, string $mobile): array
    {
        $country = self::getCountryByCallingCode($countryCode);

        if (!$country['status']) {
            return [
                'status' => false,
                'message' => __('messages.Country code not found'),
            ];
        }

        $validation = self::validate($mobile, $country['alpha_2']);

        if (!$validation['status']) {
            return [
                'status' => false,
                'message' => $validation['message'],
            ];
        }

        return [
            'status' => true,
            'number_format' => str_replace(' ', '', $validation['number_format']),
        ];
    }

    /**
     * Resolve a country calling code to its ISO Alpha-2 country code.
     *
     * The country mapping is loaded from the application's countries
     * data file and supports calling codes with or without the "+" prefix.
     *
     * @param string $countryCode Country calling code, e.g. +20 or 20.
     *
     * @return array{
     *     status: bool,
     *     alpha_2?: string
     * }
     */
    public static function getCountryByCallingCode(string $countryCode): array
    {
        $countryCode = ltrim($countryCode, '+');

        $countries = json_decode(
            file_get_contents(database_path('data/countries_data.json'))
        );

        if (!$countries || !isset($countries->{$countryCode})) {
            return [
                'status' => false,
            ];
        }

        return [
            'status' => true,
            'alpha_2' => $countries->{$countryCode}->alpha_2,
        ];
    }

    /**
     * Validate and format a phone number for the specified country.
     *
     * Uses libphonenumber to parse the number according to the provided
     * ISO Alpha-2 country code and verifies whether it is a valid number.
     *
     * @param string $phoneNumber Phone number to validate.
     * @param string $countryCode ISO Alpha-2 country code, e.g. EG, SA, AE.
     *
     * @return array{
     *     status: bool,
     *     number_format?: string,
     *     message?: string
     * }
     */
    public static function validate(string $phoneNumber, string $countryCode): array
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $number = $phoneUtil->parse(
                $phoneNumber,
                strtoupper($countryCode)
            );

            if (!$phoneUtil->isValidNumber($number)) {
                return [
                    'status' => false,
                    'message' => __('messages.Invalid phone number'),
                ];
            }

            return [
                'status' => true,
                'number_format' => $phoneUtil->format(
                    $number,
                    PhoneNumberFormat::INTERNATIONAL
                ),
            ];
        } catch (NumberParseException $exception) {
            return [
                'status' => false,
                'message' => __('messages.Invalid phone number'),
            ];
        }
    }
}
