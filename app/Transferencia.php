<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transferencia extends Model
{
    protected $table = 'transferencias';
    protected $fillable = [
        'idusuario','idusuariorecibido', 'idbodegasalida', 'idbodegaentrada', 'cantidad', 'condicion'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function user1()
    {
        return $this->belongsTo('App\User');
    }

    public function bodega()
    {
        return $this->belongsTo('App\Bodega');
    }
    public function bodega1()
    {
        return $this->belongsTo('App\Bodega');
    }
}
