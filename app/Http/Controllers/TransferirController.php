<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para poder usar transacciones
use Carbon\Carbon;
use App\Transferencia;
use App\Bodega;
use App\Idbodega;
use App\User;
use App\Total;
use Illuminate\Support\Arr;

class TransferirController extends Controller
{
  //Listar Transferencias
  public function index(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $userlogin = Auth()->user()->id;

    $idbodega1 = Idbodega::where('idusuario','=',$userlogin)
    ->select('idbodega')
    ->orderBy('id','desc')->take(1)->get();
    foreach($idbodega1 as $id)
    {
        $idbodega=$id['idbodega'];
    }

    $transferencias = Transferencia::join('bodegas','transferencias.idbodegaentrada','=','bodegas.id')
      ->select('transferencias.id','transferencias.idbodegaentrada','transferencias.cantidad','transferencias.condicion','bodegas.nombre as bodega')
      ->where('transferencias.idusuario', '=',$userlogin)
      ->where('transferencias.idbodegasalida', '=',$idbodega)
      ->where('transferencias.condicion', '=','0')
      ->orderBy('transferencias.id', 'desc')->get();
      
      return [ 
          'transferencias' => $transferencias
      ];
  }
  //Listar Ingreso
  public function listar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $userlogin = Auth()->user()->id;

    $idbodega1 = Idbodega::where('idusuario','=',$userlogin)
    ->select('idbodega')
    ->orderBy('id','desc')->take(1)->get();
    foreach($idbodega1 as $id)
    {
        $idbodega=$id['idbodega'];
    }

    $transferencias = Transferencia::join('users','transferencias.idusuario','=','users.id')
      ->join('bodegas','transferencias.idbodegasalida','=','bodegas.id')
      ->select('transferencias.id','transferencias.idusuario','transferencias.idbodegasalida',
                  'transferencias.cantidad','transferencias.condicion',
                  'users.nombre as usuario','bodegas.nombre as bodega')
      ->where('transferencias.idbodegaentrada', '=',$idbodega)
      ->where('transferencias.condicion','=','0')
      ->orderBy('transferencias.id', 'desc')->get();
      
      return [ 
          'transferencias' => $transferencias
      ];
  }

  //Inicio listado con fecha de hoy
  public function indexhoy(Request $request)
  {
    //if (!$request->ajax()) return redirect('/');
    $mytime = Carbon::now();

    $dia=$mytime->format('d');
    $mes=$mytime->format('m');
    $ano=$mytime->format('y');
    $date=$ano.-$mes.-$dia;
    $dia2=$mytime->format('d')+1;
    $mes2=$mytime->format('m');
    $ano2=$mytime->format('y');
    $date2=$ano2.-$mes2.-$dia2;
  
    $userlogin = Auth()->user()->id;
    
    $idbodega1 = Idbodega::where('idusuario','=',$userlogin)
    ->select('idbodega')
    ->orderBy('id','desc')->take(1)->get();
    foreach($idbodega1 as $id)
    {
        $idbodega=$id['idbodega'];
    }

    $transferencias = Transferencia::join('users','transferencias.idusuario','=','users.id')
    ->join('users as s','transferencias.idusuariorecibido','=','s.id')
    ->join('bodegas','transferencias.idbodegasalida','=','bodegas.id')
    ->join('bodegas as b','transferencias.idbodegaentrada','=','b.id')
    ->select('transferencias.id','transferencias.idusuario','transferencias.idusuariorecibido','transferencias.idbodegasalida',
                'transferencias.idbodegaentrada','transferencias.cantidad','transferencias.condicion','transferencias.updated_at',
                'users.nombre as usuario','s.nombre as usuariorecibido','bodegas.alias as bodegasalida','b.alias as bodegaentrada')
    ->where('transferencias.created_at', '>=',$date)
    ->where('transferencias.created_at', '<=',$date2)
    ->where('transferencias.condicion', '!=','0')
    ->where('transferencias.idbodegasalida','=', $idbodega)
    ->orderBy('transferencias.updated_at', 'desc')->get();

    $ingresos = Transferencia::join('users','transferencias.idusuario','=','users.id')
    ->join('users as s','transferencias.idusuariorecibido','=','s.id')
    ->join('bodegas','transferencias.idbodegasalida','=','bodegas.id')
    ->join('bodegas as b','transferencias.idbodegaentrada','=','b.id')
    ->select('transferencias.id','transferencias.idusuario','transferencias.idusuariorecibido','transferencias.idbodegasalida',
                'transferencias.idbodegaentrada','transferencias.cantidad','transferencias.condicion','transferencias.updated_at',
                'users.nombre as usuario','s.nombre as usuariorecibido','bodegas.alias as bodegasalida','b.alias as bodegaentrada')
    ->where('transferencias.created_at', '>=',$date)
    ->where('transferencias.created_at', '<=',$date2)
    ->where('transferencias.condicion', '!=','0')
    ->where('transferencias.idbodegaentrada', '=', $idbodega)
    //->orwhere('transferencias.idbodegaentrada', '=', $idbodega)
    ->orderBy('transferencias.updated_at', 'desc')->get();

    $a=count($transferencias);
    for ($i=0; $i < count($ingresos); $i++) 
    { 
      $transferencias[$a+$i] = $ingresos[$i];
    }

    $transferencias = array_values(Arr::sort($transferencias, function ($value){
      return $value['updated_at'];
    }));

    return ['transferencias'=>$transferencias];
  }


  //Obtener fecha hoy
  public function fechahoy(Request $request)
  {
    $mytime = Carbon::now();
    $dia=$mytime->format('d');
    $mes=$mytime->format('m');
    $ano='20'.$mytime->format('y');
    //$ano=$mytime->format('y');
    $date=$ano.-$mes.-$dia;
    $dia2=$mytime->format('d')+1;
    $mes2=$mytime->format('m');
    $ano2='20'.$mytime->format('y');
   // $ano2=$mytime->format('y');
    $date2=$ano2.-$mes2.-$dia2;
   
    return['fechainicio'=>$date,'fechafin'=>$date2];
  }

  //Inicio listado con fecha segun usuario
  public function indexfecha(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $mytime = Carbon::now();

    $fechainicio=$request->fechainicio;
    $fechafin=$request->fechafin;
    $date=substr($fechainicio, 2); 
    $date2=substr($fechafin, 2); 

    $userlogin = Auth()->user()->id;
    
    $idbodega = $request->idbodega;

    $transferencias = Transferencia::join('users','transferencias.idusuario','=','users.id')
    ->join('users as s','transferencias.idusuariorecibido','=','s.id')
    ->join('bodegas','transferencias.idbodegasalida','=','bodegas.id')
    ->join('bodegas as b','transferencias.idbodegaentrada','=','b.id')
    ->select('transferencias.id','transferencias.idusuario','transferencias.idusuariorecibido','transferencias.idbodegasalida',
                'transferencias.idbodegaentrada','transferencias.cantidad','transferencias.condicion','transferencias.updated_at',
                'users.nombre as usuario','s.nombre as usuariorecibido','bodegas.alias as bodegasalida','b.alias as bodegaentrada')
    ->where('transferencias.created_at', '>=',$date)
    ->where('transferencias.created_at', '<=',$date2)
    ->where('transferencias.condicion', '!=','0')
    ->where('transferencias.idbodegasalida','=', $idbodega)
    ->orderBy('transferencias.updated_at', 'desc')->get();

    $ingresos = Transferencia::join('users','transferencias.idusuario','=','users.id')
    ->join('users as s','transferencias.idusuariorecibido','=','s.id')
    ->join('bodegas','transferencias.idbodegasalida','=','bodegas.id')
    ->join('bodegas as b','transferencias.idbodegaentrada','=','b.id')
    ->select('transferencias.id','transferencias.idusuario','transferencias.idusuariorecibido','transferencias.idbodegasalida',
                'transferencias.idbodegaentrada','transferencias.cantidad','transferencias.condicion','transferencias.updated_at',
                'users.nombre as usuario','s.nombre as usuariorecibido','bodegas.alias as bodegasalida','b.alias as bodegaentrada')
    ->where('transferencias.created_at', '>=',$date)
    ->where('transferencias.created_at', '<=',$date2)
    ->where('transferencias.condicion', '!=','0')
    ->where('transferencias.idbodegaentrada', '=', $idbodega)
    //->orwhere('transferencias.idbodegaentrada', '=', $idbodega)
    ->orderBy('transferencias.updated_at', 'desc')->get();

    $a=count($transferencias);
    for ($i=0; $i < count($ingresos); $i++) 
    { 
      $transferencias[$a+$i] = $ingresos[$i];
    }

    $transferencias = array_values(Arr::sort($transferencias, function ($value){
      return $value['updated_at'];
    }));
    
    return ['transferencias'=>$transferencias];

  }


//Nueva transferencia
  public function store(Request $request)
  {
          
    if (!$request->ajax()) return redirect('/');

    $idbodega=$request->idbodegaentrada;

    $bodegas=Bodega::where('id','=',$idbodega)
    ->where('tipo','=','franquicia')
    ->select('id')->get();

    $bodega=count($bodegas);
    if($bodega>=1)
    {
      $transferencia = new Transferencia();
      $transferencia->idusuario = $request->idusuario;
      $transferencia->idusuariorecibido = $request->idusuario;
      $transferencia->idbodegasalida = $request->idbodegasalida;
      $transferencia->idbodegaentrada = $request->idbodegaentrada;
      $transferencia->cantidad = $request->cantidad;
      $transferencia->condicion = '1';
      $transferencia->save();
    }
    else
    {
      $transferencia = new Transferencia();
      $transferencia->idusuario = $request->idusuario;
      $transferencia->idbodegasalida = $request->idbodegasalida;
      $transferencia->idbodegaentrada = $request->idbodegaentrada;
      $transferencia->cantidad = $request->cantidad;
      $transferencia->condicion = '0';
      $transferencia->save();
    }

  }
 //Nueva transferencia admin
 public function storeAdmin(Request $request)
  {
          
    if (!$request->ajax()) return redirect('/');
      $transferencia = new Transferencia();
      $transferencia->idusuario = $request->idusuario;
      $transferencia->idusuariorecibido = $request->idusuario;
      $transferencia->idbodegasalida = $request->idbodegasalida;
      $transferencia->idbodegaentrada = $request->idbodegaentrada;
      $transferencia->cantidad = $request->cantidad;
      $transferencia->condicion = '1';
      $transferencia->save();
  }
 //Ingreso directo si la bodega es una franquicia
public function storeIngreso(Request $request)
{
  $transferencia = new Transferencia();
  $transferencia->idusuario = $request->idusuario;
  $transferencia->idusuariorecibido = $request->idusuario;
  $transferencia->idbodegasalida = $request->idbodegasalida;
  $transferencia->idbodegaentrada = $request->idbodegaentrada;
  $transferencia->cantidad = $request->cantidad;
  $transferencia->condicion = '1';
  $transferencia->save();

  
}
//Rechazar ingreso
  public function rechazar(Request $request)
  {
      if (!$request->ajax()) return redirect('/');
   
      $userlogin = Auth()->user()->id;
      $transferir = Transferencia::findOrFail($request->id);
      $transferir->idusuariorecibido=$userlogin;
      $transferir->condicion = '2';
      $transferir->save();
  }
  //Aceptar ingreso
  public function aceptar(Request $request)
  {
      if (!$request->ajax()) return redirect('/');
      $userlogin = Auth()->user()->id;
      $transferir = Transferencia::findOrFail($request->id);
      $transferir->idusuariorecibido=$userlogin;
      $transferir->condicion = '1';
      $transferir->save();
  }
  //Total de la tabla toal listar
  public function total(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    
    $total1 = Total::select('total')->get();

    foreach($total1 as $id1)
    {
        $total=$id1['total'];
    }
    
    $bodega = Bodega::where('condicion', '!=','0')
                    ->select('cantidad')->get();
    $suma=0;
    foreach($bodega as $id)
    {
        $suma=$suma+$id['cantidad'];
    }
      
      return [ 'total'=>$total, 'suma'=> $suma];
  }
}