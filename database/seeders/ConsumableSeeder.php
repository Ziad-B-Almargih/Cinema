<?php

namespace Database\Seeders;

use App\Models\Consumable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsumableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $consumables = [
            [
                'name' => 'Corn',
                'price' => 100,
                'type' => 'food',
            ],
            [
                'name' => 'Soda',
                'price' => 50,
                'type' => 'drink',
            ],
            [
                'name' => 'Nachos',
                'price' => 150,
                'type' => 'food',
            ],
            [
                'name' => 'Water',
                'price' => 30,
                'type' => 'drink',
            ]
        ];

        Consumable::insert($consumables);
    }
}
