<?php

namespace App\Enums;

enum PaymentTypeEnum: int
{
    case cash = 1;
    case debit = 2;


    public function label(): string
    {
        return match($this) {
            PaymentTypeEnum::cash => __('trans.cash'),
            PaymentTypeEnum::debit => __('trans.debit'),
        };
    }

    public function style(): string
    {
        return match($this) {
            PaymentTypeEnum::cash => 'success',
            PaymentTypeEnum::debit => 'danger',
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
