<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    
  public function chains()
  {
    return $this->belongsToMany('App\Chain','chains_categories');
  }    
}
