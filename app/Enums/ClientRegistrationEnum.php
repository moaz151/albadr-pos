<?php

namespace App\Enums;

enum ClientRegistrationEnum: int
{
    case pos = 1;
    case app = 2;


    public function label(): string
    {
        return match($this) {
            ClientRegistrationEnum::pos => __('trans.pos'),
            ClientRegistrationEnum::app => __('trans.app'),
        };
    }

    public function style(): string
    {
        return match($this) {
            ClientRegistrationEnum::pos => 'success',
            ClientRegistrationEnum::app => 'danger',
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
