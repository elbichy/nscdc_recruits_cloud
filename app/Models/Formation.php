<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany('App\Models\User')->using('App\Models\FormationUser')->withPivot('id', 'command', 'department', 'designation', 'from', 'to', 'created_at', 'updated_at');
    }
}
