<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = array(
            array('id' => '1','state_name' => 'abia','region' => 'South East','region_id' => '5'),
            array('id' => '2','state_name' => 'adamawa','region' => 'North East','region_id' => '2'),
            array('id' => '3','state_name' => 'akwa-ibom','region' => 'South South','region_id' => '6'),
            array('id' => '4','state_name' => 'anambra','region' => 'South East','region_id' => '5'),
            array('id' => '5','state_name' => 'bauchi','region' => 'North East','region_id' => '2'),
            array('id' => '6','state_name' => 'bayelsa','region' => 'South South','region_id' => '6'),
            array('id' => '7','state_name' => 'benue','region' => 'North Central','region_id' => '3'),
            array('id' => '8','state_name' => 'borno','region' => 'North East','region_id' => '2'),
            array('id' => '9','state_name' => 'cross-river','region' => 'South South','region_id' => '6'),
            array('id' => '10','state_name' => 'delta','region' => 'South South','region_id' => '6'),
            array('id' => '11','state_name' => 'ebonyi','region' => 'South East','region_id' => '5'),
            array('id' => '12','state_name' => 'edo','region' => 'South South','region_id' => '6'),
            array('id' => '13','state_name' => 'ekiti','region' => 'South West','region_id' => '4'),
            array('id' => '14','state_name' => 'enugu','region' => 'South East','region_id' => '5'),
            array('id' => '15','state_name' => 'fct','region' => 'North Central','region_id' => '3'),
            array('id' => '16','state_name' => 'gombe','region' => 'North East','region_id' => '2'),
            array('id' => '17','state_name' => 'imo','region' => 'South East','region_id' => '5'),
            array('id' => '18','state_name' => 'jigawa','region' => 'North West','region_id' => '1'),
            array('id' => '19','state_name' => 'kaduna','region' => 'North West','region_id' => '1'),
            array('id' => '20','state_name' => 'kano','region' => 'North West','region_id' => '1'),
            array('id' => '21','state_name' => 'katsina','region' => 'North West','region_id' => '1'),
            array('id' => '22','state_name' => 'kebbi','region' => 'North West','region_id' => '1'),
            array('id' => '23','state_name' => 'kogi','region' => 'North Central','region_id' => '3'),
            array('id' => '24','state_name' => 'kwara','region' => 'North Central','region_id' => '3'),
            array('id' => '25','state_name' => 'lagos','region' => 'South West','region_id' => '4'),
            array('id' => '26','state_name' => 'nasarawa','region' => 'North Central','region_id' => '3'),
            array('id' => '27','state_name' => 'niger','region' => 'North Central','region_id' => '3'),
            array('id' => '28','state_name' => 'ogun','region' => 'South West','region_id' => '4'),
            array('id' => '29','state_name' => 'ondo','region' => 'South West','region_id' => '4'),
            array('id' => '30','state_name' => 'osun','region' => 'South West','region_id' => '4'),
            array('id' => '31','state_name' => 'oyo','region' => 'South West','region_id' => '4'),
            array('id' => '32','state_name' => 'plateau','region' => 'North Central','region_id' => '3'),
            array('id' => '33','state_name' => 'rivers','region' => 'South South','region_id' => '6'),
            array('id' => '34','state_name' => 'sokoto','region' => 'North West','region_id' => '1'),
            array('id' => '35','state_name' => 'taraba','region' => 'North East','region_id' => '2'),
            array('id' => '36','state_name' => 'yobe','region' => 'North East','region_id' => '2'),
            array('id' => '37','state_name' => 'zamfara','region' => 'North West','region_id' => '1')
        );

        foreach ($states as $key => $value) {
            State::insert($value);
        }
    }
}
