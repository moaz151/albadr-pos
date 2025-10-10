<?php

namespace App\Enums;

enum SafeTypeEnum: int
{
    case cash = 1;
    case online = 2;


    public function label(): string
    {
        return match($this) {
            SafeTypeEnum::cash => 'Active',
            SafeTypeEnum::online => 'InActive',
        };
    }

    public function style(): string
    {
        return match($this) {
            SafeTypeEnum::cash => 'success',
            SafeTypeEnum::online => 'danger',
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
