<?php

namespace App\Models\configuracion;

use Illuminate\Database\Eloquent\Model;

class Tipo_Archivo extends Model
{
    public $timestamps = false;
    protected $table = 'principal.tipo_archivo';
    protected $primaryKey='id_tipo_archivo';
}
