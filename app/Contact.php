<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'address_id',
        'type',
        'surname',
        'name',
        'patronymic',
        'phones',
        'email',
        'job',
        'post',
        'address_postal',
        'address_number',
        'address_building',
        'address_apartment',
        'skype',
        'address_aoid',
    ]; 
    
    public $timestamps = false; 
    
}
