<?php

namespace App\Enums;

enum ClientStatusEnum: int
{
    case active = 1;
    case inactive = 2;


    public function label(): string
    {
        return match($this) {
            ClientStatusEnum::active => 'Active',
            ClientStatusEnum::inactive => 'InActive',
        };
    }

    public function style(): string
    {
        return match($this) {
            ClientStatusEnum::active => 'success',
            ClientStatusEnum::inactive => 'danger',
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
