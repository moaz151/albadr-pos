<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case confirmed  = 1;
    case processing  = 2;
    case shipped  = 3;
    case delivered  = 4;


    public function label(): string
    {
        return match($this) {
            OrderStatusEnum::confirmed  => __('trans.confirmed '),
            OrderStatusEnum::processing  => __('trans.processing '),
            OrderStatusEnum::shipped  => __('trans.shipped '),
            OrderStatusEnum::delivered  => __('trans.delivered '),
        };
    }

    public function style(): string
    {
        return match($this) {
            OrderStatusEnum::confirmed  => 'info',
            OrderStatusEnum::processing  => 'success',
            OrderStatusEnum::shipped  => 'danger',
            OrderStatusEnum::delivered  => 'warning',
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
