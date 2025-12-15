<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdvancedSettings extends Settings
{
    public bool $allow_decimal_quantities;

    public string $default_discount_method;
    
    /** @var array<int, string> */
    public array $payment_methods;

    public static function group(): string
    {
        return 'advanced';
    }
}


