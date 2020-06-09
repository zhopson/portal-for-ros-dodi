<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChainCategory extends Model
{
    protected $fillable = [
        'category_id',
        'chain_id',
    ]; 
    
    public $timestamps = false; 
    
    protected $table = 'chains_categories';
}
