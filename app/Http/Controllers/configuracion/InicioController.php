<?php

namespace App\Http\Controllers\configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $sesion = DB::table('usuarios')->where('id',Auth::user()->id)->where('estado',1)->get();
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_inicio')->where('id_rol',Auth::user()->id_rol)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_rol='.Auth::user()->id_rol);
            if($permisos->count() == 0)
            {
                return view('errors/sin_permiso',compact('menu','permisos'));
            }
            else if($sesion->count() == 0)
            {
                Auth::logout();
                return view('auth/login');
            }
            else
            {
                return view('configuracion/vw_inicio',compact('menu','permisos'));
            } 
        }
        else
        {
            return redirect('/');
        }    
    }

    public function show($id, Request $request)
    {
        
    }

    public function create(Request $request)
    {
        
    }

    public function edit($id_tipo_archivo,Request $request)
    {
        
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }

}
