<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IPAddress extends Model
{
    protected $table = 'ip_addresses';
    protected $fillable = [
        'clients_id',
        'address',
        'netmask',
        'gateway',
        'default',
    ];    
    public $timestamps = false;    
    
  public function client()
  {
    return $this->belongsTo('App\Client','clients_id');
  }
}
