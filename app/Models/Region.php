<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Lga;
use App\User;
class Region extends Model
{
    protected $fillable = [
        'name'
    ];

    public function states(){
        return $this->hasMany('App\Models\State', 'region_id');
    }

}
