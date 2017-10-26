<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postal extends Model
{
    protected $fillable = [
        'client_id',
        'address_id',
        'address_postal',
        'address_number',
        'address_building',
        'address_apartment',
        'address_aoid',
    ];

    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'last_choice';   
    
}

