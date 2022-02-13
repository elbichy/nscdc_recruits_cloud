<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\State;
class Lga extends Model
{
    protected $fillable = [
        'state',
        'state_id',
        'lg_name'
    ];

    public function state(){
        return $this->belongsTo('App\Models\State');
    }
}
