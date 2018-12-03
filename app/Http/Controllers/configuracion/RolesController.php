<?php

namespace App\Http\Controllers\configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\configuracion\Roles;


class RolesController extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_roles')->where('id_rol',Auth::user()->id_rol)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_rol='.Auth::user()->id_rol);
            if($permisos->count() == 0)
            {
                return view('errors/sin_permiso',compact('menu','permisos'));
            }
                return view('configuracion/vw_roles',compact('menu','permisos'));
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
        }
    }

    public function create(Request $request)
    {
        $Roles = new Roles;
        $Roles->codigo      = strtoupper($request['codigo']);
        $Roles->descripcion = strtoupper($request['descripcion']);
        
        $Roles->save();
        return $Roles ->id_rol;
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

    public function destroy(Request $request)
    {
        
    }

    public function store(Request $request)
    {
       
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

}
