<?php

namespace App\Http\Controllers\archivo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\archivo\Archivos;
use Illuminate\Support\Facades\Response;

class ArchivoController extends Controller
{
    public function index()
    {
        if( Auth::user() )
        {
            $permisos = DB::table('permisos.vw_permisos')->where('id_sistema','li_config_archivos')->where('id_rol',Auth::user()->id_rol)->get();
            $menu = DB::select('SELECT * from permisos.vw_permisos where id_rol='.Auth::user()->id_rol);
            $tipo_archivo = DB::table('principal.tipo_archivo')->get();
            if($permisos->count() == 0)
            {
                return view('errors/sin_permiso',compact('menu','permisos'));
            }
                return view('archivo/vw_archivos',compact('menu','permisos','tipo_archivo'));
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
            if ($request['grid'] == 'archivos') 
            {
                return $this->crear_tabla_archivos($request);
            }
            if ($request['mostrar'] == 'archivo') 
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
        if ($request['tipo'] == 1) 
        {
            return $this->guardar_archivos($request);
        }
    }
    
    public function ver_archivos(Request $request)
    {
        $sql = DB::table('principal.vw_archivos')->where('id_archivo',$request['id_archivo'])->first();
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
    
    public function descargar_archivos($id_archivo)
    {
        $archivo = DB::table('principal.vw_archivos')->where('id_archivo',$id_archivo)->first();
        if ($archivo) 
        {
            //dd($archivo);
            //return \Storage::response(storage_path('app/public/' . $archivo->usuario . '/' . $archivo->archivo));
            //return \Storage::download(storage_path('app/' . $archivo->ruta));
            //$file = storage_path('app/' . $archivo->ruta);
            return \Storage::download($archivo->ruta);
            //return Response::download($file);
            //return Response::download($file, 'filename.pdf', $headers);
            //return response()->download($file);
        }
        else 
        {
            return 0;
        }
    }
    
    public function crear_tabla_archivos(Request $request)
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
        $totalg = DB::select("select count(*) as total from principal.vw_archivos where descripcion like '%".strtoupper($request['descripcion'])."%' and id_usuario = '".Auth::user()->id."'");
        $sql = DB::table('principal.vw_archivos')->where('descripcion','like', '%'.strtoupper($request['descripcion']).'%')->where('id_usuario',Auth::user()->id)->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $nuevo = '<a class="btn btn-labeled btn-danger" style="text-decoration: none;color:white;" href="'.route('download',$Datos->id_archivo).'" ><span class="btn-label"><i class="fa fa-print"></i></span> DESCARGAR</a>';
            $ver = '<button class="btn btn-labeled btn-success" type="button" onclick="ver_archivos('.trim($Datos->id_archivo).')"><span class="btn-label"><i class="fa fa-search"></i></span> VER ARCHIVO</button>';
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_archivo),
                trim($Datos->descripcion),
                trim($Datos->archivo),
                trim($Datos->tipo_archivo),
                trim($Datos->fecha_registro),
                $nuevo,
                $ver  
            );
        }
        return response()->json($Lista);
    }
    
    public function guardar_archivos(Request $request)
    {
        if($request->hasFile('file'))
        { 
            foreach($request->file as $file)
            {
                $nombre = $file->getClientOriginalName();
                $tipo = $file->getClientMimeType();

                $ruta = $file->storeAs('public/' . Auth::user()->usuario,date('Y-m-d'). '_' .$nombre);
                //$r= \Storage::disk('local')->put($nombre,  \File::get($file));
                
                $Archivos                  = new Archivos;
                $Archivos->id_usuario      = Auth::user()->id;
                $Archivos->id_tipo_archivo = $request['id_tipo_archivo'];
                $Archivos->descripcion     = strtoupper($request['descripcion']);
                $Archivos->archivo         = date('Y-m-d'). '_' .$nombre;
                $Archivos->mimetype        = $tipo;
                $Archivos->ruta            = $ruta;
                $Archivos->fecha_registro  = date('Y-m-d');
                $Archivos->save();
            }
            return 1;
        } 
        else
        {
            return 0;
        }
    }
}
