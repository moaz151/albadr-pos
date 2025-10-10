<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Safe;

use App\Enums\SafeStatusEnum;
use App\Enums\SafeTypeEnum;
class SafesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $safes = [
            'Cash',
            'InstaPay 0100100',
            'VodaCash 0100200',
        ];
        foreach ($safes as $safe) {
            Safe::updateOrCreate([
                'name' => $safe
            ], [
                'name' => $safe,
                'status' => SafeStatusEnum::active,
                'balance' => 0,
                'description' => null,
                'type' => $safe == 'Cash' ? SafeTypeEnum::cash : SafeTypeEnum::online,
            ]);
        }
    }
}
