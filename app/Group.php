<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  public function clients()
  {
    return $this->belongsToMany('App\Client','groups_clients', 'groups_id', 'clients_id');
  }
}
