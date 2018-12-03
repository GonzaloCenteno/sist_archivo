<?php

namespace App\Http\Controllers\archivo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\archivo\ArchivoPersona;
use Illuminate\Support\Facades\Response;

class Asignar_Archivos_Controller extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_asignar_archivos')->where('id_rol',Auth::user()->id_rol)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_rol='.Auth::user()->id_rol);
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
            if ($request['show'] == 'archivo_persona') 
            {
                return $this->recuperar_datos_archivo_persona($id);
            }
            if ($request['show'] == 'verificar_usuario') 
            {
                return $this->verificar_datos_usuario($id);
            }
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
            if ($request['grid'] == 'archivo_persona') 
            {
                return $this->crear_tabla_archivo_persona($request);
            }
            if ($request['grid'] == 'archivos_asignados') 
            {
                return $this->crear_tabla_archivos_asignados($request);
            }
            if ($request['grid'] == 'nuevas_asignaciones') 
            {
                return $this->crear_tabla_nuevas_asignaciones($request);
            }
        }
    }

    public function create(Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->guardar_datos_archivos_asignados($request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->guardar_datos_asignacion_archivos($request);
        }
    }

    public function edit($id_usuario,Request $request)
    {
        $ArchivoPersona = new ArchivoPersona;
        $val=  $ArchivoPersona::where("id_arch_pers","=",$request['id_arch_pers'])->where("id_usuario","=",$request['id_usuario'])->where("id_archivo","=",$request['id_archivo'])->first();
        if($val)
        {
            $val->flag = $request['flag'];
            $val->save();
        }
        return $id_usuario;  
    }

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
    }
    
    public function recuperar_datos_archivo_persona($id_usuario)
    {
        $usuario = DB::table('principal.vw_archivo_persona')->where('id',$id_usuario)->get();
        return $usuario;
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
                    "<input type='checkbox' style='width:100%' name='id_archivo_check' id_archivo = '$Datos->id_archivo'>"
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
    
    public function crear_tabla_archivo_persona(Request $request)
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
        $totalg = DB::select("select count(*) as total from principal.vw_archivo_persona where persona like '%".strtoupper($request['nombre'])."%'");
        $sql = DB::table('principal.vw_archivo_persona')->where('persona','like', '%'.strtoupper($request['nombre']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->id; 
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id),
                trim($Datos->dni),
                trim($Datos->persona),
                trim($Datos->cargo),
                trim($Datos->usuario),
                trim($Datos->email),
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_archivos_asignados(Request $request)
    {
        header('Content-type: application/json');
        $id_tipo_archivo = $request['id_tipo_archivo'];
        $id_usuario = $request['id_usuario'];
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $start = ($limit * $page) - $limit; // do not put $limit*($page - 1)  
        if ($start < 0) {
            $start = 0;
        }
        $totalg = DB::select("select count(*) as total from principal.vw_archivos_asignados where id_tipo_archivo = '$id_tipo_archivo' and id_usuario = '$id_usuario'");
        $sql = DB::table('principal.vw_archivos_asignados')->where('id_tipo_archivo',$id_tipo_archivo)->where('id_usuario',$id_usuario)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->id_archivo; 
            if ($Datos->flag == 1) {
                $nuevo = "<input type='checkbox' style='width:100%' name='estado' checked='true' id_archivo = '$Datos->id_archivo' id_arch_pers = '$Datos->id_arch_pers'>";
            }else{
                $nuevo = "<input type='checkbox' style='width:100%' name='estado' id_archivo = '$Datos->id_archivo' id_arch_pers = '$Datos->id_arch_pers'>";
            }
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_archivo),
                trim($Datos->descripcion),
                trim($Datos->archivo),
                trim($Datos->fecha_registro),
                $nuevo,
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_nuevas_asignaciones(Request $request)
    {
        header('Content-type: application/json');
        $id_tipo_archivo = $request['id_tipo_archivo'];
        $id_usuario = $request['id_usuario'];
        $totalg = DB::select("select count(*) as total from principal.archivo where id_archivo not in(select id_archivo from principal.vw_archivos_asignados where id_usuario = '$id_usuario') and id_tipo_archivo = '$id_tipo_archivo'");
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
        
        $sql = DB::select("select * from principal.archivo where id_archivo not in(select id_archivo from principal.vw_archivos_asignados where id_usuario = '$id_usuario') and id_tipo_archivo = '$id_tipo_archivo' order by $sidx $sord limit $limit offset $start");
        
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
                "<input type='checkbox' style='width:100%' name='asignados' id_archivo = '$Datos->id_archivo'>"
            );
        }

        return response()->json($Lista);
    }
    
    public function guardar_datos_archivos_asignados(Request $request)
    {
        $ArchivoPersona = new ArchivoPersona;
        $ArchivoPersona->id_archivo = $request['id_archivo'];
        $ArchivoPersona->id_usuario = $request['id_usuario'];
        $ArchivoPersona->flag       = $request['flag'];
        $ArchivoPersona->save();
        return $ArchivoPersona->id_arch_pers;
    }
    
    public function guardar_datos_asignacion_archivos(Request $request)
    {
        $ArchivoPersona = new ArchivoPersona;
        $ArchivoPersona->id_archivo = $request['id_archivo'];
        $ArchivoPersona->id_usuario = $request['id_usuario'];
        $ArchivoPersona->flag       = $request['flag'];
        $ArchivoPersona->save();
        return $ArchivoPersona->id_arch_pers;
    }
    
    public function verificar_datos_usuario($id_usuario)
    {
        $sql = DB::table('principal.vw_archivo_persona')->where('id',$id_usuario)->first();
        
        if ($sql) 
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }
}
