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
        'sip_number',
        'sip_secret',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','sip_secret',
    ];
    
  public function roles()
  {
    return $this->belongsToMany('App\Role','users_roles', 'user_id', 'role_id');
  }

  public function hasRole($role)
  {
      
      $r = $this->roles;
      $rr = $r->where('role', '=' ,$role)->first();
      if ($rr == null) return false;
      else return true;
  }
  
  public function client()
  {
    return $this->HasOne('App\Client');
  }

  public function chains()
  {
    return $this->HasMany('App\Chain');
  }

  public function who_closed()
  {
    return $this->HasMany('App\Chain','who_closed');
  }
  
  }
