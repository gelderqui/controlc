<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Bodega;
use App\Idbodega;
use App\Control;


class BodegaController extends Controller
{
    //Lista principal de bodega
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $bodegas = Bodega::orderBy('id', 'desc')->paginate(8);
        }
        else{
            $bodegas = Bodega::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(8);
        }
        

        return [
            'pagination' => [
                'total'        => $bodegas->total(),
                'current_page' => $bodegas->currentPage(),
                'per_page'     => $bodegas->perPage(),
                'last_page'    => $bodegas->lastPage(),
                'from'         => $bodegas->firstItem(),
                'to'           => $bodegas->lastItem(),
            ],
            'bodegas' => $bodegas
        ];
        
    }
    //Listar bodega a seleccionar en modal del usario
    public function listarBodega(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $bodegas = Bodega::orderBy('id', 'desc')->paginate(8);
        }
        else{
            $bodegas = Bodega::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(8);
        }
        return ['bodegas' => $bodegas];
    }
    //Buscar bodega con alias en usuario
    public function buscarBodega(Request $request)
    {
     //   if (!$request->ajax()) return redirect('/');
        $buscar=$request->buscar;
        $criterio=$request->criterio;
       // $buscar=2;
        //$criterio='id';
        $bodegas=Bodega::where($criterio,'=', $buscar)
        ->select('id','tipo','nombre','alias','direccion','cantidad','condicion')
        ->OrderBy('nombre','asc')->take(1)->get();
        
        return['bodegas'=>$bodegas];
    }
    //Lista de bodegas a seleccionar al crear nueva tranferencia
    public function selectBodega(Request $request)
    {

        $userlogin = Auth()->user()->id;

        $idbodega1 = Idbodega::where('idusuario','=',$userlogin)
        ->select('idbodega')
        ->orderBy('id','desc')->take(1)->get();
        foreach($idbodega1 as $id)
        {
            $idbodega=$id['idbodega'];
        }

        $bodegas = Bodega::where('condicion','=','1')
        ->where('id','!=',$idbodega)
        ->select('id','nombre','alias')
        ->orderBy('alias','asc')->get();

        return['bodegas'=>$bodegas];
    }
    //Buscar si bodega es franquicia
    public function buscarIngreso(Request $request)
    {
        $idbodega=$request->id;
        $idusuario=$request->idusuario;
        
        $bodegas = Control::join('bodegas','controles.idbodega','bodegas.id')
        ->where('controles.idusuario','=',$idusuario)
        ->where('bodegas.tipo','=','franquicia')
        ->select('bodegas.id')->get();

        $bandera=count($bodegas);

        if($bandera>=1)$bandera=1;
        else $bandera=0;
        
        return ['bandera'=>$bandera];

    }

    //Listar bodegas que sean franquicia
    public function listarFranquicia(Request $request)
    {
        
        $bodegas=Bodega::where('tipo','=','franquicia')
        ->select('id','nombre','alias')
        ->orderBy('alias','asc')->get();
        
        return ['bodegas'=>$bodegas];

    }

    //Listar todas las bodegas existentes
    public function listartodo(Request $request)
    {
        $bodegas = Bodega::where('condicion','=','1')
        ->select('id','alias')
        ->orderBy('alias','asc')->get();

        return['bodegas'=>$bodegas];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $bodega = new Bodega();
        $bodega->tipo = $request->tipo;
        $bodega->nombre = $request->nombre;
        $bodega->alias = $request->alias;
        $bodega->direccion = $request->direccion;
        $bodega->cantidad = $request->cantidad;
        $bodega->condicion = '1';
        $bodega->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $bodega = Bodega::findOrFail($request->id);
        $bodega->tipo = $request->tipo;
        $bodega->nombre = $request->nombre;
        $bodega->alias = $request->alias;
        $bodega->direccion = $request->direccion;
        $bodega->cantidad = $request->cantidad;
        $bodega->condicion = '1';
        $bodega->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $bodega = Bodega::findOrFail($request->id);
        $bodega->condicion = '0';
        $bodega->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $bodega = Bodega::findOrFail($request->id);
        $bodega->condicion = '1';
        $bodega->save();
    }

}
