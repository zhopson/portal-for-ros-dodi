<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
//    protected $dateFormat = 'U';
    
    protected $fillable = [
        'role',
    ];
    
  public function users()
  {
    return $this->belongsToMany('App\User','users_roles', 'role_id', 'user_id');
  }
}
