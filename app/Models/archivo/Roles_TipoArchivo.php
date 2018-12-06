<?php

namespace App\Models\archivo;

use Illuminate\Database\Eloquent\Model;

class Roles_TipoArchivo extends Model
{
    public $timestamps = false;
    protected $table = 'principal.roles_tipo_archivo';
    protected $primaryKey='id_rol_tip_arch';
}
