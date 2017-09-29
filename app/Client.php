<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  public function user()
  {
    return $this->belongsTo('App\User');
  }
  
  public function chains()
  {
    return $this->HasMany('App\Chain');
  }

  public function tasks()
  {
    return $this->HasMany('App\Task');
  }

  public function groups()
  {
    return $this->belongsToMany('App\Group','groups_clients', 'clients_id', 'groups_id');
  }
}
