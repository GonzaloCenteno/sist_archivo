<?php

namespace App\Http\Controllers\permisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\permisos\Modulos;
class ModulosController extends Controller
{
    public function index(Request $request)
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
        $totalg = DB::select("select count(*) as total from permisos.modulos");
        $sql = DB::table('permisos.modulos')->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
            $Lista->rows[$Index]['id'] = $Datos->id_mod;            
            $Lista->rows[$Index]['cell'] = array(
                trim($Datos->id_mod),
                trim($Datos->descripcion),
            );
        }
        return response()->json($Lista);
    }

    public function create(Request $request)
    {
        $modulos=new Modulos;
        $modulos->descripcion=$request['des'];
        $modulos->titulo=$request['tit'];
        $modulos->id_sistema=$request['sis'];
        $modulos->save();
        return $modulos->id_mod;
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        $modulos= DB::table('permisos.modulos')->where('id_mod',$id)->get();
        return $modulos;
    }

   
    public function edit(Request $request,$id)
    {
        $modulos=new Modulos;
        $val=  $modulos::where("id_mod","=",$id )->first();
        if($val)
        {
            $val->descripcion=$request['des'];
            $val->titulo=$request['tit'];
            $val->id_sistema=$request['sis'];
            $val->save();
       
        }
        return $id;
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request)
    {   
        $modulos=new Modulos;
        $val=  $modulos::where("id_mod","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
        return "destroy ".$request['id'];
    }
}
