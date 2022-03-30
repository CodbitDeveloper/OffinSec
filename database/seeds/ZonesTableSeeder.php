<?php

use App\Zone;
use Illuminate\Database\Seeder;

class ZonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Zone::create(["name" => "Northern Zone"]);
        Zone::create(["name" => "Southern Zone"]);
    }
}
