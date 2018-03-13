<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{

    protected $fillable = [
        'name',
        'watch',
    ]; 
    
  public $timestamps = false;     
    //
}
