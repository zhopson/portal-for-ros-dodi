<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupClient extends Model
{
    protected $table = 'groups_clients';
        protected $fillable = [
        'clients_id',
        'groups_id',
    ];
        public $timestamps = false;    
}
