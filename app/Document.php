<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    
    protected $fillable = [
        'category_id',
        'header',
        'description',
    ];

    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'update_time';    
    
}
