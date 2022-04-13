<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Redeployment extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql2';
    protected $guarded = [];

    public function setFullnameAttribute($value)
    {
        $value = strtolower($value);
        $this->attributes['fullname'] = ucwords($value);
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
