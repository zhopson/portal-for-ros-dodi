<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentAttach extends Model
{
    protected $table = 'documents_attach';
    
    protected $fillable = [
        'document_id',
        'description',
        'path',
        'type',
        'visible_filename',
    ];

    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'update_time';    
        

}
