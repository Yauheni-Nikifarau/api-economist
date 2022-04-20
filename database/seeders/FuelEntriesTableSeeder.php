<?php

namespace Database\Seeders;

use App\Models\FuelEntry;
use Illuminate\Database\Seeder;

class FuelEntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FuelEntry::factory()->count(5)->create();
    }
}
