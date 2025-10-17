<?php

namespace App\Enums;

enum DiscountTypeEnum: int
{
    case percentage = 1;
    case fixed = 2;


    public function label(): string
    {
        return match($this) {
            DiscountTypeEnum::percentage => __('trans.percentage'),
            DiscountTypeEnum::fixed => __('trans.fixed'),
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
