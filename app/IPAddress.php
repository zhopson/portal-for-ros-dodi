<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPAddress extends Model
{
        protected $table = 'ip_addresses';
        
  public function client()
  {
    return $this->belongsTo('App\Client','clients_id');
  }
}
