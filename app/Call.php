<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    const CREATED_AT = 'creation_time';
    const UPDATED_AT = null;
    
    protected $dateFormat = 'U';
    
    protected $fillable = [
        'user_id',
        'client_id',
        'chain_id',
        'comment',
        'status',
        'incoming',
        'interlocutor',
        'contract_id',
    ];

    public function client() {
        return $this->belongsTo('App\Client');
    }   
}
