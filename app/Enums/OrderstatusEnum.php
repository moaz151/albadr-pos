<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case pending = 0;      // client can cancel
    case confirmed = 1;     // Admin confirmed - cannot cancel
    case processing = 2;    // Cannot cancel
    case shipped = 3;       // Cannot cancel
    case delivered = 4;     // Cannot cancel
    case cancelled = 5;


    public function label(): string
    {
        return match($this) {
            OrderStatusEnum::pending => __('trans.pending'),
            OrderStatusEnum::confirmed => __('trans.confirmed'),
            OrderStatusEnum::processing => __('trans.processing'),
            OrderStatusEnum::shipped => __('trans.shipped'),
            OrderStatusEnum::delivered => __('trans.delivered'),
            OrderStatusEnum::cancelled => __('trans.cancelled'),
        };
    }

    public function style(): string
    {
        return match($this) {
            OrderStatusEnum::pending  => 'warning',
            OrderStatusEnum::confirmed  => 'primary',
            OrderStatusEnum::processing  => 'success',
            OrderStatusEnum::shipped  => 'info',
            OrderStatusEnum::delivered  => 'success',
            OrderStatusEnum::cancelled  => 'danger',
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
