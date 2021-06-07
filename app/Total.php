<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
        protected $table = 'totales';
    //protected $primaryKey = 'id';
    protected $fillable = ['total'];
}
