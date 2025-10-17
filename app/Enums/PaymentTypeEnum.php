<?php

namespace App\Enums;

enum PaymentTypeEnum: int
{
    case cash = 1;
    case debt = 2;


    public function label(): string
    {
        return match($this) {
            PaymentTypeEnum::cash => __('trans.cash'),
            PaymentTypeEnum::debt => __('trans.debt'),
        };
    }

    public function style(): string
    {
        return match($this) {
            PaymentTypeEnum::cash => 'success',
            PaymentTypeEnum::debt => 'danger',
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
