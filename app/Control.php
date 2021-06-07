<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    protected $table = 'controles';
    protected $fillable = ['idusuario','idbodega'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }
}
