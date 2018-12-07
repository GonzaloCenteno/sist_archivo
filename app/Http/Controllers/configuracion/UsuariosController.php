<?php

namespace App\Http\Controllers\configuracion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\configuracion\Usuarios;


class UsuariosController extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_usuarios')->where('id_rol',Auth::user()->id_rol)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_rol='.Auth::user()->id_rol);
            $roles = DB::table('principal.roles')->orderBy('descripcion','asc')->get();
            if($permisos->count() == 0)
            {
                return view('errors/sin_permiso',compact('menu','permisos'));
            }
                return view('configuracion/vw_usuarios',compact('menu','permisos','roles'));
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
            if ($request['show'] == 'datos_usuario') 
            {
                return $this->traer_datos_usuario($id, $request);
            }
            if ($request['show'] == 'resetear_clave') 
            {   
                return $this->resetar_clave_usuario($id);
            }
        }
        else
        {
            if ($request['grid'] == 'usuarios') 
            {
                return $this->crear_tabla_usuarios($request);
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
        if ($request['tipo'] == 1) 
        {
            return $this->guardar_datos_usuario($request);
        }
        if ($request['tipo'] == 2) 
        {
            return $this->cambiar_foto_usuario($request);
        }
        if ($request['tipo'] == 3) 
        {
            return $this->cambiar_pass_user($request);
        }
        if ($request['tipo'] == 4) 
        {
            return $this->editar_datos_usuario($request);
        }
    }
    
    public function editar_datos_usuario(Request $request)
    {
        $sql = DB::table('usuarios')->where('dni',$request['form_dni_edit'])->where('id','<>',$request['id_usuario'])->get();
        
        if ($sql->count() > 0) {
            return response()->json([
                'msg' => 'si',
            ]);
        }
        else
        {
            $usuario = DB::table('usuarios')->where('usuario', strtoupper($request['form_usuario_edit']))->where('id','<>',$request['id_usuario'])->get();
            if ($usuario->count() > 0) 
            {
                return response()->json([
                'msg' => 'usuario_ok',
                ]);
            }
            else
            {
                $Usuario = new  Usuarios;
                $val=  $Usuario::where("id","=",$request['id_usuario'])->first();
                $datos = DB::table('usuarios')->where('id',$request['id_usuario'])->get();
                if($val)
                {
                    $file = $request->file('form_foto_edit');
                    $val->dni         = $request['form_dni_edit'];
                    $val->nombres     = strtoupper($request['form_nombres_edit']);
                    $val->apaterno    = strtoupper($request['form_apaterno_edit']);
                    $val->amaterno    = strtoupper($request['form_amaterno_edit']);
                    $val->email       = strtoupper($request['form_email_edit']);
                    $val->usuario     = strtoupper($request['form_usuario_edit']);
                    $val->id_rol      = $request['form_cargo_edit'];

                    if ($file) {
                        $file_1 = \File::get($file);
                        $val->foto = base64_encode($file_1);
                    }else{
                        $val->foto = $datos[0]->foto;
                    }
                    $val->save();
                }
                return $request['id_usuario'];
            }
        }
    }
    
    public function cambiar_pass_user(Request $request){
        $id = Auth::user()->id;
        $pass = trim($request['pass1']);

        $update = DB::table('usuarios')->where('id',$id)->update(['password'=> bcrypt($pass)]);
        if ($update) {
            return response()->json(['msg' => 'si']);
        } else {
            return response()->json(['msg' => 'no','id'=>$id]);
        }
    }
    
    public function cambiar_foto_usuario(Request $request){
        $file = $request->file('vw_usuario_cambiar_cargar_foto');
        $file2 = \File::get($file);        
        
        $id = Auth::user()->id;
        $foto = base64_encode($file2);

        $update = DB::table('usuarios')->where('id',$id)->update(['foto'=>$foto]);
        if ($update) {
            return response()->json(['msg' => 'si']);
        } else {
            return response()->json(['msg' => 'no','id'=>$id]);
        }
    }
    
    public function guardar_datos_usuario(Request $request)
    {
        $select=DB::table('usuarios')->where('dni',strtoupper($request['form_dni']))->get();

        if ($select->count() > 0) 
        {
            return response()->json([
                'msg' => 'si',
            ]);
        }
        else
        {
            $usuario = DB::table('usuarios')->where('usuario',strtoupper($request['form_usuario']))->get();
            if ($usuario->count() > 0) 
            {
                return response()->json([
                'msg' => 'usuario_ok',
                ]);
            }   
            else
            {
                $file = $request->file('form_foto');
                $Usuario = new  Usuarios;
                $Usuario->dni         = $request['form_dni'];
                $Usuario->nombres     = strtoupper($request['form_nombres']);
                $Usuario->apaterno    = strtoupper($request['form_apaterno']);
                $Usuario->amaterno    = strtoupper($request['form_amaterno']);
                $Usuario->email       = strtoupper($request['form_email']);
                $Usuario->password    = bcrypt($request['form_password']);
                $Usuario->id_rol      = $request['form_cargo'];
                $Usuario->usuario     = strtoupper($request['form_usuario']);

                if ($file) {
                    $file_1 = \File::get($file);
                    $Usuario->foto = base64_encode($file_1);
                }else{
                    $Usuario->foto = "-";
                }


                $Usuario->save();
                return $Usuario->id; 
            }
        } 
    }
    
    public function traer_datos_usuario($id_usuario, Request $request)
    {
        $usuario = DB::table('usuarios')->where('id',$id_usuario)->get();
        return $usuario;
    }
    
    public function crear_tabla_usuarios(Request $request)
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
        $totalg = DB::select("select count(*) as total from vw_usuarios where persona like '%".strtoupper($request['persona'])."%'");
        $sql = DB::table('vw_usuarios')->where('persona','like', '%'.strtoupper($request['persona']).'%')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            if ($Datos->estado == 1) 
            {
                $var = 'ACTIVO';
            }
            else
            {
                $var = 'INACTIVO';
            }
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id),
                trim($Datos->dni),
                trim($Datos->persona),
                trim($Datos->email),
                trim($Datos->cargo),
                trim($Datos->usuario),
                $var,
                trim($Datos->id_rol),
            );
        }
        return response()->json($Lista);
    }
    
    public function resetar_clave_usuario($id_usuario)
    {
        $Usuario = new  Usuarios;
        $val=  $Usuario::where("id","=",$id_usuario)->first();
        if($val)
        {
            $val->password = bcrypt('123456');
            $val->save();
        }
        return $id_usuario;
    }

}
