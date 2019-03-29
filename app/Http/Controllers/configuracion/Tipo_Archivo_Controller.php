<?php

namespace App\Http\Controllers\configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\configuracion\Tipo_Archivo;


class Tipo_Archivo_Controller extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $sesion = DB::table('usuarios')->where('id',Auth::user()->id)->where('estado',1)->get();
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_tipo_archivo')->where('id_rol',Auth::user()->id_rol)->get();
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
                return view('configuracion/vw_tipo_archivo',compact('menu','permisos'));
            }  
        }
        else
        {
            return redirect('/');
        }    
    }

    public function show($id, Request $request)
    {
        if ($id > 0) 
        {
            if ($request['show'] == 'tipos_archivo') 
            {
                return $this->traer_datos_tipo_archivo($id);
            }
        }
        else
        {
            if ($request['grid'] == 'tipo_archivo') 
            {
                return $this->crear_tabla_tipo_archivo($request);
            }
        }
    }

    public function create(Request $request)
    {
        $select=DB::table('principal.tipo_archivo')->where('descripcion',strtoupper($request['descripcion']))->get();

        if ($select->count() > 0) 
        {
            return response()->json([
                'msg' => 'si',
            ]);
        }
        else
        {
            $Tipo_Archivo = new  Tipo_Archivo;
            $Tipo_Archivo->descripcion = strtoupper($request['descripcion']);

            $Tipo_Archivo->save();
            return $Tipo_Archivo->id_tipo_archivo;
        }
    }

    public function edit($id_tipo_archivo,Request $request)
    {
        $sql = DB::table('principal.tipo_archivo')->where('descripcion',strtoupper($request['descripcion']))->where('id_tipo_archivo','<>',$id_tipo_archivo)->get();
        
        if ($sql->count() > 0) 
        {
            return response()->json([
                'msg' => 'si',
            ]);
        }
        else
        {
            $Tipo_Archivo = new Tipo_Archivo;
            $val=  $Tipo_Archivo::where("id_tipo_archivo","=",$id_tipo_archivo)->first();
            if($val)
            {
                $val->descripcion = strtoupper($request['descripcion']);
                $val->save();
            }
            return $id_tipo_archivo;
        }
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function traer_datos_tipo_archivo($id_tipo_archivo)
    {
        $tipo_archivo = DB::table('principal.tipo_archivo')->where('id_tipo_archivo',$id_tipo_archivo)->get();
        return $tipo_archivo;
    }
    
    public function crear_tabla_tipo_archivo(Request $request)
    {
        header('Content-type: application/json');
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        $totalg = DB::select("select count(*) as total from principal.tipo_archivo");
        $sql = DB::table('principal.tipo_archivo')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

        $total_pages = 0;
        if (!$sidx) {
            $sidx = 1;
        }
        $count = $totalg[0]->total;
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $Lista = new \stdClass();
        $Lista->page = $page;
        $Lista->total = $total_pages;
        $Lista->records = $count;
        foreach ($sql as $Index => $Datos) {
            $Lista->rows[$Index]['id'] = $Datos->id_tipo_archivo;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_tipo_archivo),
                trim($Datos->descripcion)
            );
        }
        return response()->json($Lista);
    }

}
