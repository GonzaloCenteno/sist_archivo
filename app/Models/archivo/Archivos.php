<?php

namespace App\Models\archivo;

use Illuminate\Database\Eloquent\Model;

class Archivos extends Model
{
    public $timestamps = false;
    protected $table = 'principal.archivo';
    protected $primaryKey='id_archivo';
}
