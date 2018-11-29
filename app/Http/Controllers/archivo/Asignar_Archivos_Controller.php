<?php

namespace App\Http\Controllers\archivo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\archivo\Archivos;
use Illuminate\Support\Facades\Response;

class Asignar_Archivos_Controller extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_asignar_archivos')->where('id_usu',Auth::user()->id)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_usu='.Auth::user()->id);
            $tipo_archivo = DB::table('principal.tipo_archivo')->orderBy('descripcion', 'asc')->get();
            if($permisos->count() == 0)
            {
                return view('errors/sin_permiso',compact('menu','permisos'));
            }
                return view('archivo/vw_asignar_archivos',compact('menu','permisos','tipo_archivo'));
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
            
        }
        else
        {
            if ($request['grid'] == 'usuarios') 
            {
                return $this->crear_tabla_usuarios($request);
            }
            if ($request['grid'] == 'asignar_archivos') 
            {
                return $this->crear_tabla_asignacion_archivos($request);
            }
        }
    }

    public function create(Request $request)
    {
    
    }

    public function edit($id_usuario,Request $request)
    {
              
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function crear_tabla_asignacion_archivos(Request $request)
    {
        if ($request['id_tipo_archivo'] == '0') 
        {
            header('Content-type: application/json');
            $id_tipo_archivo = $request['id_tipo_archivo'];
            $totalg = DB::select("select count(*) as total from principal.archivo where id_tipo_archivo = 0");
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];

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
            $start = ($limit * $page) - $limit; 
            if ($start < 0) {
                $start = 0;
            }

            $sql = DB::table('principal.archivo')->where('id_tipo_archivo',0)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;

            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_archivo;
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_archivo),
                    trim($Datos->descripcion),
                    trim($Datos->archivo),
                    trim($Datos->fecha_registro),
                    "<input type='checkbox' name='id_archivo_check' id_archivo = '$Datos->id_archivo'>"
                );
            }

            return response()->json($Lista);
        }
        else
        {
            header('Content-type: application/json');
            $id_tipo_archivo = $request['id_tipo_archivo'];
            $totalg = DB::select("select count(*) as total from principal.archivo where id_tipo_archivo = '$id_tipo_archivo'");
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];

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
            $start = ($limit * $page) - $limit; 
            if ($start < 0) {
                $start = 0;
            }

            $sql = DB::table('principal.archivo')->where('id_tipo_archivo',$id_tipo_archivo)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;

            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id_archivo;
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_archivo),
                    trim($Datos->descripcion),
                    trim($Datos->archivo),
                    trim($Datos->fecha_registro),
                    "<input type='checkbox' name='id_archivo_check' id_archivo = '$Datos->id_archivo'>"
                );
            }

            return response()->json($Lista);
        }
    }
    
    public function crear_tabla_usuarios(Request $request)
    {
        if($request['nombre']=='0')
        {
            return 0;
        }
        else
        {
            header('Content-type: application/json');
            $totalg = DB::select("select count(*) as total from vw_usuarios where persona like '%".strtoupper($request['nombre'])."%'");
            $page = $_GET['page'];
            $limit = $_GET['rows'];
            $sidx = $_GET['sidx'];
            $sord = $_GET['sord'];

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
            $start = ($limit * $page) - $limit;  
            if ($start < 0) {
                $start = 0;
            }

            $sql = DB::table('vw_usuarios')->where('persona','like', '%'.strtoupper($request['nombre']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();
            $Lista = new \stdClass();
            $Lista->page = $page;
            $Lista->total = $total_pages;
            $Lista->records = $count;


            foreach ($sql as $Index => $Datos) {
                $Lista->rows[$Index]['id'] = $Datos->id;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id),
                    trim($Datos->dni),
                    trim($Datos->persona),
                    trim($Datos->cargo),
                    trim($Datos->usuario),
                );
            }
            return response()->json($Lista);
        }
    }
    
}
