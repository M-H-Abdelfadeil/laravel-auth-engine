<?php

namespace App\Traits;

trait EnumHelpers
{
    /**
     * Get all enum values.
     *
     * Example:
     * UserRoleEnum::values();
     * // ['super_admin', 'admin']
     *
     * @return array<int, mixed>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get enum key => value pairs.
     *
     * Example:
     * UserRoleEnum::key_values();
     * // ['SUPER_ADMIN' => 'super_admin', 'ADMIN' => 'admin']
     *
     * @return array<string, mixed>
     */
    public static function key_values(): array
    {
        $items = [];

        foreach (self::cases() as $case) {
            $items[$case->name] = $case->value;
        }

        return $items;
    }

    /**
     * Get enum names (case names only).
     *
     * Example:
     * UserRoleEnum::names();
     * // ['SUPER_ADMIN', 'ADMIN']
     *
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Check if given value exists in enum.
     *
     * Example:
     * UserRoleEnum::is_valid_value('admin'); // true
     * UserRoleEnum::is_valid_value('user');  // false
     *
     * @param mixed $value
     * @return bool
     */
    public static function is_valid_value($value): bool
    {
        return in_array($value, self::values());
    }

    /**
     * Convert enum to structured array (name & value).
     *
     * Example:
     * UserRoleEnum::to_array();
     * // [
     * //     ['name' => 'SUPER_ADMIN', 'value' => 'super_admin'],
     * //     ['name' => 'ADMIN', 'value' => 'admin'],
     * // ]
     *
     * @return array<int, array{name: string, value: mixed}>
     */
    public static function to_array(): array
    {
        return collect(self::cases())
            ->map(fn($case) => [
                'name' => $case->name,
                'value' => $case->value,
            ])
            ->toArray();
    }
}
