<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices = array(
            array('id' => '1', 'name' => 'Commandant General', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '2', 'name' => 'DCG Administration', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '3', 'name' => 'DCG Operations', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '4', 'name' => 'DCG Intelligence & Investigation', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '5', 'name' => 'DCG Critical Assets & Infrastructure', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '6', 'name' => 'DCG Disaster & Crisis Management', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '7', 'name' => 'DCG Technical Services', 'level' => 'Directorate', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '8', 'name' => 'ACG Administration', 'level' => 'Department', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '9', 'name' => 'ACG Operations', 'level' => 'Department', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '10', 'name' => 'ACG Intelligence & Investigation', 'level' => 'Department', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '11', 'name' => 'ACG Critical Assets & Infrastructure', 'level' => 'Department', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '12', 'name' => 'ACG Disaster & Crisis Management', 'level' => 'Department', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '13', 'name' => 'ACG Technical Services', 'level' => 'Department', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '14', 'name' => 'CC Administration', 'level' => 'Unit', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '15', 'name' => 'CC Operations', 'level' => 'Unit', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '16', 'name' => 'CC Intelligence & Investigation', 'level' => 'Unit', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '17', 'name' => 'CC Critical Assets & Infrastructure', 'level' => 'Unit', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '18', 'name' => 'CC Disaster & Crisis Management', 'level' => 'Unit', 'created_at' => NULL, 'updated_at' => NULL),
            array('id' => '19', 'name' => 'CC Technical Services', 'level' => 'Unit', 'created_at' => NULL, 'updated_at' => NULL),
          );

        foreach ($offices as $key => $value) {
            Office::insert($value);
        }
    }
}
