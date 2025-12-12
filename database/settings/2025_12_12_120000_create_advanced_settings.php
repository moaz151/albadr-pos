<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('advanced.allow_decimal_quantities', false);
        $this->migrator->add('advanced.default_discount_method', 'percentage');
        $this->migrator->add('advanced.payment_methods', []);

    }
};


