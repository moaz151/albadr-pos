<?php

namespace App\Enums;

enum WarehouseStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            WarehouseStatusEnum::active => 'Active',
            WarehouseStatusEnum::inactive => 'InActive',
        };
    }

    public function style(): string
    {
        return match($this) {
            WarehouseStatusEnum::active => 'success',
            WarehouseStatusEnum::inactive => 'danger',
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
