<?php

namespace App\Models\archivo;

use Illuminate\Database\Eloquent\Model;

class ArchivoPersona extends Model
{
    public $timestamps = false;
    protected $table = 'principal.archivo_persona';
    protected $primaryKey='id_arch_pers';
}
