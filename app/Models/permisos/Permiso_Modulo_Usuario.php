<?php

namespace App\Models\permisos;

use Illuminate\Database\Eloquent\Model;

class Permiso_Modulo_Usuario extends Model
{
 public $timestamps = false;
    protected $table = 'permisos.permiso_modulo_usuario';
    protected $primaryKey='id_permiso';
}
