<?php

namespace App\Enums;

enum WarehouseTransactionTypeEnum: int
{
    case init = 1;
    case add = 2;
    case sub = 3;
    case adjust = 4;


    public function label(): string
    {
        return match($this) {
            WarehouseTransactionTypeEnum::init => __('trans.initial'),
            WarehouseTransactionTypeEnum::addn => __('trans.addition'),
            WarehouseTransactionTypeEnum::seb => __('trans.removal'),
            WarehouseTransactionTypeEnum::adjust => __('trans.adjustment'),
        };
    }

    public function style(): string
    {
        return match($this) {
            WarehouseTransactionTypeEnum::init => 'info',
            WarehouseTransactionTypeEnum::add => 'success',
            WarehouseTransactionTypeEnum::sub => 'danger',
            WarehouseTransactionTypeEnum::adjust => 'warning',
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
