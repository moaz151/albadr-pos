<?php

namespace App\Enums;

enum CategoryStatusEnum: int
{
    case active = 1;
    case inactive = 2;

    public function label(): string
    {
        return match($this) {
            CategoryStatusEnum::active => 'Active',
            CategoryStatusEnum::inactive => 'Inactive',
        };
    }

    public function style(): string
    {
        return match($this) {
            CategoryStatusEnum::active => 'success',
            CategoryStatusEnum::inactive => 'danger',
        };
    }

    public static function labels(): array
    {
        // return [
        //     self::active->value => self::active->label(),
        //     self::inactive->value => self::inactive->label(),
        // ];

        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }
        return $labels;
    }
}
