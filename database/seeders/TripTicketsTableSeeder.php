<?php

namespace Database\Seeders;

use App\Models\TripTicket;
use Illuminate\Database\Seeder;

class TripTicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TripTicket::factory()->count(5)->create();
    }
}
