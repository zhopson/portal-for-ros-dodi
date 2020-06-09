<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request_ extends Model
{
    
    protected $table = 'requests';
    
    const CREATED_AT = 'creation_time';
    const UPDATED_AT = null;
    
    protected $dateFormat = 'U';
    
    protected $fillable = [
        'user_id',
        'client_id',
        'chain_id',
        'comment',
        'provider_id',
        'contract_id',
    ];

    public function client() {
        return $this->belongsTo('App\Client');
    }    
}
