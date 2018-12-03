<?php

namespace App\Http\Controllers\archivo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class Ver_Archivos_Controller extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_ver_archivos')->where('id_rol',Auth::user()->id_rol)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_rol='.Auth::user()->id_rol);
            if($permisos->count() == 0)
            {
                return view('errors/sin_permiso',compact('menu','permisos'));
            }
                return view('archivo/vw_ver_archivos',compact('menu','permisos'));
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
            if ($request['grid'] == 'ver_archivos') 
            {
                return $this->crear_tabla_ver_archivos($request);
            }
            if ($request['mostrar'] == 'ver_archivos') 
            {
                return $this->ver_archivos($request);
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
    
    public function ver_archivos(Request $request)
    {
        $sql = DB::table('principal.vw_ver_archivos')->where('id_arch_pers',$request['id_arch_pers'])->where('id_usuario',$request['id_usuario'])->first();
        if($sql)
        {
            $ruta = \Storage::response($sql->ruta);
            return $ruta;
        }
        else
        {
            return "No hay Archvos";
        }
    }
    
    public function descargar_archivos_asignados($id_arch_pers)
    {
        $archivo = DB::table('principal.vw_ver_archivos')->where('id_arch_pers',$id_arch_pers)->first();
        if ($archivo) 
        {
            return \Storage::download($archivo->ruta);
        }
        else 
        {
            return 0;
        }
    }
    
    public function crear_tabla_ver_archivos(Request $request)
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
        $totalg = DB::select("select count(*) as total from principal.vw_ver_archivos where descripcion like '%".strtoupper($request['descripcion'])."%' and id_usuario = '".Auth::user()->id."' and flag = 1");
        $sql = DB::table('principal.vw_ver_archivos')->where('descripcion','like', '%'.strtoupper($request['descripcion']).'%')->where('id_usuario',Auth::user()->id)->where('flag',1)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->id_arch_pers;
            if ($Datos->mimetype == 'text/plain' || $Datos->mimetype == 'application/pdf' || $Datos->mimetype == 'image/png' || $Datos->mimetype == 'image/jpeg' || $Datos->mimetype == 'image/svg+xml' || $Datos->mimetype == 'video/mp4' || $Datos->mimetype == 'audio/mp3' || $Datos->mimetype == 'audio/ogg') 
            {
                $nuevo = '<button class="btn btn-labeled btn-danger" type="button" onclick="ver_archivos_asignados('.trim($Datos->id_arch_pers).','.trim($Datos->id_usuario).')"><span class="btn-label"><i class="fa fa-search"></i></span> VER ARCHIVO</button>';
            }
            else 
            {
                $nuevo = '<a class="btn btn-labeled btn-success" style="text-decoration: none;color:white;" href="'.route('desc_archivos_asignados',$Datos->id_arch_pers).'" ><span class="btn-label"><i class="fa fa-print"></i></span> DES. ARCHIVO</a>';
            }
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_arch_pers),
                trim($Datos->descripcion),
                trim($Datos->archivo),
                trim($Datos->tipo_archivo),
                trim($Datos->fecha_registro),
                $nuevo
            );
        }
        return response()->json($Lista);
    }
}
