<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.company_name', 'Mezolux');
        $this->migrator->add('general.company_email', 'info@mezolux.com');
        $this->migrator->add('general.company_phone', '+2348130000000');
        $this->migrator->add('general.company_logo', 'logo.png');
    }
};
