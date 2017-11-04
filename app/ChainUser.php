<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChainUser extends Model
{
    protected $fillable = [
        'user_id',
        'chain_id',
    ]; 
    
    public $timestamps = false; 
    
    protected $table = 'chains_users';
}
