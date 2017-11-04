<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'update_time';
    
    protected $dateFormat = 'U';
    
    protected $fillable = [
        'who_closed',
        'status',
        'user_id',
        'client_id',
        'opening_time',
        'last_comment',
        'deleted',
        'closing_time',
        'last_item_id',
        'last_call_id',
        'has_request',
        'on_request',
        'contract_id',
    ];    
    
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
  
  public function categories()
  {
    return $this->belongsToMany('App\Category','chains_categories');
  }

  
  
  }
