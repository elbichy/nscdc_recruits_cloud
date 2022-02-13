<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{

    protected $guarded = [];
    
    public function getDobAttribute($value){
        $date = strtotime($value);
        return date('Y-m-d', $date);
    }
    public function getDofaAttribute($value){
        $date = strtotime($value);
        return date('Y-m-d', $date);
    }
    public function getDopaAttribute($value){
        $date = strtotime($value);
        return date('Y-m-d', $date);
    }
}
