<?php

namespace App\Models\permisos;

use Illuminate\Database\Eloquent\Model;

class Sub_Modulos extends Model
{
    public $timestamps = false;
    protected $table = 'permisos.sub_modulos';
    protected $primaryKey='id_sub_mod';
}
