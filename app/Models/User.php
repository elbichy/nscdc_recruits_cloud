<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at', 'dob', 'dofa', 'dopa'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ranks(){
        return $this->hasOne('App\Models\Rank');
    }
    
    public function redeployments(){
        return $this->hasMany('App\Models\Redeployment');
    }
    
    public function correspondences(){
        return $this->hasMany('App\Models\Correspondence');
    }

    public function documents(){
        return $this->hasMany('App\Models\Document');
    }
    
    public function formations(){
        return $this->belongsToMany('App\Models\Formation')->using('App\Models\FormationUser')->withPivot('id', 'command', 'department', 'designation', 'from', 'to', 'created_at', 'updated_at')->orderByPivot('from', 'desc');
    }
    
    // public function deployments(){
    //     return $this->hasMany('App\Models\Deployment');
    // }
    
    public function noks(){
        return $this->hasMany('App\Models\Nok');
    }
    
    public function children(){
        return $this->hasMany('App\Models\Children');
    }
    
    public function progressions(){
        return $this->hasMany('App\Models\Progression')->orderBy('effective_date', 'desc');
    }
    
    public function qualifications(){
        return $this->hasMany('App\Models\Qualification')->orderBy('year_obtained', 'desc');;
    }

    // public function setPhoneNumberAttribute($value)
    // {
    //     return '0'.$value;
    // }

    public function getNameAttribute($value)
    {
        $name = strtolower($value);
        return ucwords($name);
    }

    public function getSexAttribute($value)
    {
        $sex = strtolower($value);
        return ucwords($sex);
    }
    
    // public function getBankAttribute($value)
    // {
    //     $abbreviation = "";
    //     $string = ucwords($value);
    //     $words = explode(" ", "$string");
    //     // dd($words);
    //     if(count($words) >=3){
    //         foreach($words as $word){
    //             $abbreviation .= $word[0];
    //         }
    //         return $abbreviation;
    //     }else{
    //         return $value;
    //     }
    // }
    
    public function getDobAttribute($value)
    {
        return date("Y-m-d", strtotime($value));
    }

    public function getDofaAttribute($value)
    {
        return date("Y-m-d", strtotime($value));
    }
    
    public function getDocAttribute($value)
    {
        return date("Y-m-d", strtotime($value));
    }

    public function getDopaAttribute($value)
    {
        return date("Y-m-d", strtotime($value));
    }
}
