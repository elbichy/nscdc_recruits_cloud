<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = array(
            array('id' => '1','name' => 'North West','abr' => 'nw'),
            array('id' => '2','name' => 'North East','abr' => 'ne'),
            array('id' => '3','name' => 'North Central','abr' => 'nc'),
            array('id' => '4','name' => 'South West','abr' => 'sw'),
            array('id' => '5','name' => 'South East','abr' => 'se'),
            array('id' => '6','name' => 'South South','abr' => 'ss')
        );

        foreach ($regions as $key => $value) {
            Region::insert($value);
        }
    }
}
