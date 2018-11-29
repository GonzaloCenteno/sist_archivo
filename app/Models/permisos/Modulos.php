<?php

namespace App\Models\permisos;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
    public $timestamps = false;
    protected $table = 'permisos.modulos';
    protected $primaryKey='id_mod';
}
