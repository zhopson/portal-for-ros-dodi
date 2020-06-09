<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'update_time';
    
    protected $dateFormat = 'U';
    
    protected $fillable = [
//        'creation_time',
//        'update_time',
        'priority',
        'status',
        'user_id',
        'responsible_id',
        'client_id',
        'chain_id',
        'message',
        'master_id',
        'start_time',
        'deadline_time',
        'progress',
        'departure',
        'contract_id',
    ];

    public function client() {
        return $this->belongsTo('App\Client');
    }

}
