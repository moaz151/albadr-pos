<?php

namespace App\Enums;

enum SaleTypeEnum: int
{
    case sale = 1;
    case return = 2;


    public function label(): string
    {
        return match($this) {
            SaleTypeEnum::sale => __('trans.sale'),
            SaleTypeEnum::return => __('trans.return'),
        };
    }

    public function style(): string
    {
        return match($this) {
            SaleTypeEnum::sale => 'success',
            SaleTypeEnum::return => 'danger',
        };
    }

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }
        return $labels;
    }

}
