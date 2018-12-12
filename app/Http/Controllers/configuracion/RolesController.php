<?php

namespace App\Http\Controllers\configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\configuracion\Roles;
use App\Models\archivo\Roles_TipoArchivo;


class RolesController extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $sesion = DB::table('usuarios')->where('id',Auth::user()->id)->where('estado',1)->get();
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_roles')->where('id_rol',Auth::user()->id_rol)->get();
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
                return view('configuracion/vw_roles',compact('menu','permisos'));
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
            if ($request['show'] == 'datos_roles') 
            {
                return $this->traer_datos_roles($id);
            }
        }
        else
        {
            if ($request['grid'] == 'roles') 
            {
                return $this->crear_tabla_roles($request);
            }
            if ($request['grid'] == 'tipo_archivos') 
            {
                return $this->crear_tabla_tipo_archivos($request);
            }
        }
    }

    public function create(Request $request)
    {
        if ($request['tipo'] == 1) 
        {
            return $this->crear_roles($request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->crear_roles_tipo_archivo($request);
        }
    }

    public function edit($id_rol,Request $request)
    {
        $Roles = new Roles;
        $val=  $Roles::where("id_rol","=",$id_rol)->first();
        if($val)
        {
            $val->codigo      = strtoupper($request['codigo']);
            $val->descripcion = strtoupper($request['descripcion']);
            $val->save();
        }
        return $id_rol;
    }

    public function destroy($id_rol_tip_arch)
    {
        $Roles_TipoArchivo = new Roles_TipoArchivo;
        $val=  $Roles_TipoArchivo::where("id_rol_tip_arch","=",$id_rol_tip_arch)->first();
        if($val)
        {
            $val->delete();
        }
        return "destroy ".$id_rol_tip_arch;
    }

    public function store(Request $request)
    {
       
    }
    
    public function crear_roles_tipo_archivo(Request $request)
    {   
        $Roles_TipoArchivo = new Roles_TipoArchivo;
        $val=  $Roles_TipoArchivo::where("id_tipo_archivo","=",$request['id_tipo_archivo'])->where("id_rol","=",$request['id_rol'])->first();
        $btn='btn_'.$request['tip'];
        if($val)
        {
            $val->$btn=$request['val'];
            if($val->btn_est==0)
            {
                return $this->destroy($val->id_rol_tip_arch);
            }
            else
            {
                $val->save();
                return "edit_".$val->id_rol_tip_arch;
            }
        }
        else
        {   
            $Roles_TipoArchivo->id_rol = $request['id_rol'];
            $Roles_TipoArchivo->id_tipo_archivo = $request['id_tipo_archivo'];
            $Roles_TipoArchivo->$btn = $request['val'];

            $Roles_TipoArchivo->save();
            return $Roles_TipoArchivo->id_rol_tip_arch;
        }
    }
    
    public function crear_roles(Request $request)
    {
        $Roles = new Roles;
        $Roles->codigo      = strtoupper($request['codigo']);
        $Roles->descripcion = strtoupper($request['descripcion']);
        
        $Roles->save();
        return $Roles ->id_rol;
    }
    
    public function traer_datos_roles($id_rol)
    {
        $roles = DB::table('principal.roles')->where('id_rol',$id_rol)->get();
        return $roles;
    }
    
    public function crear_tabla_roles(Request $request)
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
        $totalg = DB::select("select count(*) as total from principal.roles");
        $sql = DB::table('principal.roles')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->id_rol;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_rol),
                trim($Datos->codigo),
                trim($Datos->descripcion),
            );
        }
        return response()->json($Lista);
    }
    
    public function crear_tabla_tipo_archivos(Request $request)
    {
        if ($request['id_rol'] == 0) 
        {
            return 0;
        }
        else
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
            $totalg = DB::select("select count(*) as total from  principal.tipo_archivo");
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
                $roles_tipo_usuario = $sql = DB::table('principal.roles_tipo_archivo')->where('id_tipo_archivo',$Datos->id_tipo_archivo)->where('id_rol',$request['id_rol'])->get();
                if(count($roles_tipo_usuario)>= 1)
                {   
                    $check="";
                    if($roles_tipo_usuario[0]->btn_est==1){$check='checked="checked"';}
                    $new='<input style="height:30px; width:100%" id="ckest_'.$Datos->id_tipo_archivo.'" type="checkbox" '.$check.' onchange="cambiar_estado('.$Datos->id_tipo_archivo.','."'est'".')">';
                }
                else
                {
                    $new='<input style="height:30px; width:100%" id="ckest_'.$Datos->id_tipo_archivo.'" type="checkbox" onchange="cambiar_estado('.$Datos->id_tipo_archivo.','."'est'".')">';
                }
                $Lista->rows[$Index]['id'] = $Datos->id_tipo_archivo;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_tipo_archivo),
                    trim($Datos->descripcion),
                    $new,
                );
            }
            return response()->json($Lista);
        }
    }

}
