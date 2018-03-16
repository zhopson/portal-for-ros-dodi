<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $fillable = [
        'name',
    ]; 
    
  public $timestamps = false;         
    
  public function clients()
  {
    return $this->belongsToMany('App\Client','groups_clients', 'groups_id', 'clients_id');
  }
}
