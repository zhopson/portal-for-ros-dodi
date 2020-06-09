<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'role_id',
    ];    
    
    protected $table = 'users_roles';
}
