<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
  public function client()
  {
    return $this->hasOne('App\Client');
  }
  
}
