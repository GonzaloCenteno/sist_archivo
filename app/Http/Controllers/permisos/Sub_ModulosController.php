<?php

namespace App\Http\Controllers\permisos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\permisos\Sub_Modulos;

class Sub_ModulosController extends Controller
{

    public function index(Request $request)
    {
        if($request['identifi']==0)
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
            $totalg = DB::select("select count(*) as total from  permisos.sub_modulos where id_mod=".$request['identifi']);
            $sql = DB::table('permisos.sub_modulos')->where('id_mod',$request['identifi'])->orderBy($sidx, $sord)->limit($limit)->offset($start)->get();

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
                $permisosusu=$sql = DB::table('permisos.permiso_modulo_usuario')->where('id_sub_mod',$Datos->id_sub_mod)->where('id_rol',$request['id_rol'])->get();
                if(count($permisosusu)>= 1)
                {   
                    $check="";
                    if($permisosusu[0]->btn_new==1){$check='checked="checked"';}
                    $new='<input style="height:30px; width:100%" id="cknew_'.$Datos->id_sub_mod.'" type="checkbox" '.$check.' onchange="actbtn('.$Datos->id_sub_mod.','."'new'".')">';
                    $check="";
                    if($permisosusu[0]->btn_edit==1){$check='checked="checked"';}
                    $edit='<input style="height:30px; width:100%" id="ckedit_'.$Datos->id_sub_mod.'" '.$check.' type="checkbox" onchange="actbtn('.$Datos->id_sub_mod.','."'edit'".')">';
                    $check="";
                    if($permisosusu[0]->btn_del==1){$check='checked="checked"';}
                    $del='<input style="height:30px; width:100%" id="ckdel_'.$Datos->id_sub_mod.'" '.$check.' type="checkbox" onchange="actbtn('.$Datos->id_sub_mod.','."'del'".')">';
                }
                else
                {
                    $new='<input style="height:30px; width:100%" id="cknew_'.$Datos->id_sub_mod.'" type="checkbox" onchange="actbtn('.$Datos->id_sub_mod.','."'new'".')">';
                    $edit='<input style="height:30px; width:100%" id="ckedit_'.$Datos->id_sub_mod.'" type="checkbox" onchange="actbtn('.$Datos->id_sub_mod.','."'edit'".')">';
                    $del='<input style="height:30px; width:100%" id="ckdel_'.$Datos->id_sub_mod.'" type="checkbox" onchange="actbtn('.$Datos->id_sub_mod.','."'del'".')">';
                }
                $Lista->rows[$Index]['id'] = $Datos->id_sub_mod;            
                $Lista->rows[$Index]['cell'] = array(
                    trim($Datos->id_sub_mod),
                    trim($Datos->des_sub_mod),
                    $new,
                    $edit,
                    $del,
                );
            }
            return response()->json($Lista);
        }
    }

     public function create(Request $request)
    {
        $sub_modulos=new Sub_Modulos;
        $sub_modulos->des_sub_mod=$request['des'];
        $sub_modulos->titulo=$request['tit'];
        $sub_modulos->id_sistema=$request['sis'];
        $sub_modulos->ruta_sis=$request['ruta'];
        $sub_modulos->id_mod=$request['mod'];
        $sub_modulos->orden=$request['orden'];
        $sub_modulos->save();
        return $sub_modulos->id_sub_mod;
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $submodulos= DB::table('permisos.sub_modulos')->where('id_sub_mod',$id)->get();
        return $submodulos;
    }


    public function edit(Request $request,$id)
    {
        $sub_modulos=new Sub_Modulos;
        $val=  $sub_modulos::where("id_sub_mod","=",$id )->first();
        if($val)
        {
            $val->des_sub_mod=$request['des'];
            $val->titulo=$request['tit'];
            $val->id_sistema=$request['sis'];
            $val->ruta_sis=$request['ruta'];
            $val->orden=$request['orden'];
            $val->save();
        }
        return $id;
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy(Request $request)
    {   
         $sub_modulos=new Sub_Modulos;
        $val=  $sub_modulos::where("id_sub_mod","=",$request['id'] )->first();
        if(count($val)>=1)
        {
            $val->delete();
        }
        return "destroy ".$request['id'];
    }
}
