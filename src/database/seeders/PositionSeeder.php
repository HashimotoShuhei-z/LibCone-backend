<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run()
    {
        Position::create([
            'position_name' => 'Manager',
        ]);

        Position::create([
            'position_name' => 'Engineer',
        ]);

        Position::create([
            'position_name' => 'Sales',
        ]);
    }
}
