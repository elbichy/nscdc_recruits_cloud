<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function getCommandAttribute($value)
    {
        $command = strtolower($value);
        return ucwords($command);
    }
}
