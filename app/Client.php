<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'clients_type_id',
        'surname',
        'name',
        'patronymic',
        'sex',
        'mother',
        'father',
        'language',
    ];

    const CREATED_AT = 'creation_time';
    const UPDATED_AT = 'updating_time';

  public function user()
  {
    return $this->belongsTo('App\User');
  }
  
  public function chains()
  {
    return $this->HasMany('App\Chain');
  }

  public function tasks()
  {
    return $this->HasMany('App\Task');
  }

  public function requests()
  {
    return $this->HasMany('App\Request_');
  }

  public function calls()
  {
    return $this->HasMany('App\Call');
  }

  public function groups()
  {
    return $this->belongsToMany('App\Group','groups_clients', 'clients_id', 'groups_id');
  }
  
  public function ipadddresses()
  {
    return $this->hasMany('App\IPAddress', 'clients_id')->orderBy('id');
  }
  
  public function contract()
  {
    return $this->belongsTo('App\Contract');
  }
  
  public function notes()
  {
    return $this->HasMany('App\Note');
  }

  
}
