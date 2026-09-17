<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        Machine::create(['name' => 'Máquina 1', 'status' => 'available']);
        Machine::create(['name' => 'Máquina 2', 'status' => 'available']);
        Machine::create(['name' => 'Máquina 3', 'status' => 'available']);
        Machine::create(['name' => 'Máquina 4', 'status' => 'available']);
        Machine::create(['name' => 'Máquina 5', 'status' => 'out_of_service']);
    }
}
