<?php

namespace App\Models\configuracion;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    public $timestamps = false;
    protected $table = 'usuarios';
    protected $primaryKey='id';
}
