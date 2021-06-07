<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table ='bodegas';
    protected $fillable=['tipo','nombre','alias','direccion','cantidad','condicion']; 


    public function control()
    {
        return $this->hasMany('App\Control');
    }

  /*  public function users()
    {
        return $this->hasMany('App\User');
    }

    public function notificacion()
    {
        return $this->hasMany('App\Notificacion');
    }
    */
}
