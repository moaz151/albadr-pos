<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Enums\UnitStatusEnum;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'Piece',
            'Kilogram',
            'Litre',
            'Box',
        ];
        foreach ($units as $unit) {
            Unit::updateOrCreate([
                'name' => $unit
            ], [
                'name' => $unit,
                'status' => UnitStatusEnum::active,
            ]);
        }
    }
}
