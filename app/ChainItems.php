<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChainItems extends Model
{
    
    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'update_time';
    
    protected $dateFormat = 'U';
    
    protected $fillable = [
        'chain_id',
        'user_id',
        'call_id',
        'task_id',
        'request_id',
        'note_id',
        'type',
        'message',
    ];     
    
    protected $table = 'chain_items';
}
