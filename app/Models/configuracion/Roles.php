<?php

namespace App\Models\configuracion;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    public $timestamps = false;
    protected $table = 'principal.roles';
    protected $primaryKey='id_rol';
}
