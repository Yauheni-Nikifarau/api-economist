<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(CarsTableSeeder::class);
        $this->call(DriversTableSeeder::class);
        $this->call(FuelEntriesTableSeeder::class);
        $this->call(FuellingsTableSeeder::class);
        $this->call(TripTicketsTableSeeder::class);
    }
}
