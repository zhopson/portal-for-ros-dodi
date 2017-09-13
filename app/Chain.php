<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function client()
  {
    return $this->belongsTo('App\Client');
  }

  public function user_closed()
  {
    return $this->belongsTo('App\User','who_closed');
  }
  
  }
