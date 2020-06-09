<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    
    protected $fillable = [
        'partner_name',
        'number',
        'date',
        'description',
        'title',
    ]; 
    
  public $timestamps = false; 
    
  public function client()
  {
    return $this->hasOne('App\Client');
  }
  
}
