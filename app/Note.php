<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'update_time';
    
    protected $dateFormat = 'U';
    
    protected $fillable = [
        'user_id',
        'client_id',
        'chain_id',
        'body',
        'stripped',
        'contract_id',
    ];
    
    public function client() {
        return $this->belongsTo('App\Client');
    }    
    
}
