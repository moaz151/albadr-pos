<?php

namespace App\Enums;

enum UnitStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            UnitStatusEnum::active => 'Active',
            UnitStatusEnum::inactive => 'InActive',
        };
    }

    public function style(): string
    {
        return match($this) {
            UnitStatusEnum::active => 'success',
            UnitStatusEnum::inactive => 'danger',
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
