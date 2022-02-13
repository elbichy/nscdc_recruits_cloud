<?php

namespace Database\Seeders;

use App\Models\Formation;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formations = array(
            array('id' => '1','formation' => 'National Headquarters','parent' => NULL,'type' => 'nhq','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '2','formation' => 'Zone A','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '3','formation' => 'Zone B','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '4','formation' => 'Zone C','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '5','formation' => 'Zone D','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '6','formation' => 'Zone E','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '7','formation' => 'Zone F','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '8','formation' => 'Zone G','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '9','formation' => 'Zone H','parent' => NULL,'type' => 'zone','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '10','formation' => 'Abia','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '11','formation' => 'Adamawa','parent' => 'zone-c','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '12','formation' => 'Akwa-Ibom','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '13','formation' => 'Anambra','parent' => 'zone-g','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '14','formation' => 'Bauchi','parent' => 'zone-c','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '15','formation' => 'Bayelsa','parent' => 'zone-g','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '16','formation' => 'Benue','parent' => 'zone-h','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '17','formation' => 'Borno','parent' => 'zone-c','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '18','formation' => 'Cross-River','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '19','formation' => 'Delta','parent' => 'zone-g','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '20','formation' => 'Ebonyi','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '21','formation' => 'Edo','parent' => 'zone-g','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '22','formation' => 'Ekiti','parent' => 'zone-f','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '23','formation' => 'Enugu','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '24','formation' => 'Fct','parent' => NULL,'type' => 'fct','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '25','formation' => 'Gombe','parent' => 'zone-c','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '26','formation' => 'Imo','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '27','formation' => 'Jigawa','parent' => 'zone-b','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '28','formation' => 'Kaduna','parent' => 'zone-b','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '29','formation' => 'Kano','parent' => 'zone-b','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '30','formation' => 'Katsina','parent' => 'zone-b','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '31','formation' => 'Kebbi','parent' => 'zone-d','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '32','formation' => 'Kogi','parent' => 'zone-h','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '33','formation' => 'Kwara','parent' => 'zone-d','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '34','formation' => 'Lagos','parent' => 'zone-a','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '35','formation' => 'Nasarawa','parent' => 'zone-h','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '36','formation' => 'Niger','parent' => 'zone-d','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '37','formation' => 'Ogun','parent' => 'zone-f','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '38','formation' => 'Ondo','parent' => 'zone-f','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '39','formation' => 'Osun','parent' => 'zone-f','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '40','formation' => 'Oyo','parent' => 'zone-f','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '41','formation' => 'Plateau','parent' => 'zone-h','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '42','formation' => 'Rivers','parent' => 'zone-e','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '43','formation' => 'Sokoto','parent' => 'zone-d','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '44','formation' => 'Taraba','parent' => 'zone-h','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '45','formation' => 'Yobe','parent' => 'zone-c','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '46','formation' => 'Zamfara','parent' => 'zone-d','type' => 'state','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '47','formation' => 'CDA Sauka','parent' => 'nhq','type' => 'college','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '48','formation' => 'CSM Abeokuta','parent' => 'nhq','type' => 'college','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '49','formation' => 'CPDM Katsina','parent' => 'nhq','type' => 'college','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '50','formation' => 'ELO Lagos','parent' => 'nhq','type' => 'edu_liason','level' => NULL,'created_at' => NULL,'updated_at' => NULL),
            array('id' => '51','formation' => 'ELO Ibadan','parent' => 'nhq','type' => 'edu_liason','level' => NULL,'created_at' => NULL,'updated_at' => NULL)
          );

        foreach ($formations as $key => $value) {
            Formation::insert($value);
        }
    }
}
