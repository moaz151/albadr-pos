<?php

namespace App\Enums;

enum SafeStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            SafeStatusEnum::active => 'Active',
            SafeStatusEnum::inactive => 'InActive',
        };
    }

    public function style(): string
    {
        return match($this) {
            SafeStatusEnum::active => 'success',
            SafeStatusEnum::inactive => 'danger',
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
