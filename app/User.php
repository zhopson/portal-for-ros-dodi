<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'is_subscribed',
        'is_admin',
        'status_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
  public function roles()
  {
    return $this->belongsToMany('App\Role','users_roles', 'user_id', 'role_id');
  }

  public function client()
  {
    return $this->HasOne('App\Client');
  }

  public function chains()
  {
    return $this->HasMany('App\Chain');
  }
  
  }
