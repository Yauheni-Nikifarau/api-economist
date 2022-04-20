<?php

namespace Database\Seeders;

use App\Models\Fuelling;
use Illuminate\Database\Seeder;

class FuellingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fuelling::factory()->count(5)->create();
    }
}
