<?php

namespace App\Enums;

enum UserStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            UserStatusEnum::active => 'Active',
            UserStatusEnum::inactive => 'InActive',
        };
    }

    public function style(): string
    {
        return match($this) {
            UserStatusEnum::active => 'success',
            UserStatusEnum::inactive => 'danger',
        };
    }

    public static function labels() :array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }
        return $labels;
    }
}
