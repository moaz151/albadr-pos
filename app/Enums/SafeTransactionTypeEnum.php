<?php

namespace App\Enums;

enum SafeTransactionTypeEnum: int
{
    case in = 1;
    case out = -1;


    public function label(): string
    {
        return match($this) {
            SafeTypeEnum::in => __('trans.cash_in'),
            SafeTypeEnum::out => __('trans.cash_out'),
        };
    }

    public function style(): string
    {
        return match($this) {
            SafeTypeEnum::in => 'success',
            SafeTypeEnum::out => 'danger',
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
