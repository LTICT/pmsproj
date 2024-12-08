<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    //use Notifiable, HasRoles;
    const CREATED_AT = 'usr_create_time';
    const UPDATED_AT = 'usr_update_time';
/**
     * The database table used by the model.
     *
     * @var string
     */
protected $table = 'tbl_users';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'usr_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
   

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    

    /*public function roleList()
    {
        return $this->hasMany(Role::class,'id');
    }*/
    
      /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
