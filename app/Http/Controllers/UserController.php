<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para poder usar transacciones
use Carbon\Carbon;
use App\User;
use App\Control;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $usuarios = User::join('roles','users.idrol','=','roles.id')
            //->join('sucursales','users.idsucursal','=','sucursales.id')
            ->select('users.id','users.nombre','users.usuario','users.password','users.idrol',
                     'roles.nombre as rol','users.condicion')
            ->orderBy('users.id', 'desc')->paginate(8);
        }
        else{
            $usuarios = User::join('roles','users.idrol','=','roles.id')
            //->join('sucursales','users.idsucursal','=','sucursales.id')
            ->select('users.id','users.nombre','users.usuario','users.password','users.idrol',
                     'roles.nombre as rol','users.condicion')
            ->where('users.'.$criterio, 'like','%'.$buscar.'%')
            ->orderBy('users.id', 'desc')->paginate(8);
        }
        

        return [
            'pagination' => [
                'total'        => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page'     => $usuarios->perPage(),
                'last_page'    => $usuarios->lastPage(),
                'from'         => $usuarios->firstItem(),
                'to'           => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
        
    }

    public function store(Request $request)
    {
            
           if (!$request->ajax()) return redirect('/');
         try{
            DB::beginTransaction();
            $user = new User();
            $user->nombre = $request->nombre;
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->idrol = $request->idrol;
            $user->condicion = '1';
            $user->save();
       
            $detalles = $request->data;//Array de bodega
            //Recorro todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new Control();
                $detalle->idusuario = $user->id;
                $detalle->idbodega = $det['idbodega'];       
                $detalle->save();
            }            
            DB::commit();
        } catch (Exception $e){
                DB::rollBack();
        }
           
      
    }
    public function obtenerDetalle(Request $request)
    {   
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = Control::join('users','controles.idusuario','=','users.id')
        ->join('bodegas','controles.idbodega','=','bodegas.id')
        ->select('bodegas.id as idbodega','bodegas.tipo','bodegas.nombre as bodega','bodegas.alias','bodegas.condicion')
        ->where('controles.idusuario','=',$id)
        ->orderBy('controles.idbodega','asc')->get();;

        return['detalles'=>$detalles]; 
    }   

    public function update(Request $request)
    {
         if (!$request->ajax()) return redirect('/');
        try{
            $idusuario=$request->id;

            $user = User::findOrFail($request->id);
            $user->nombre = $request->nombre;
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->idrol = $request->idrol;
            $user->condicion = '1';
            $user->save();

            $detalles = $request->data;//Array de bodega
            //Recorro todos los elementos
            foreach($detalles as $ep=>$det)
            {
                $detalle = new Control();
                $detalle->idusuario = $idusuario;
                $detalle->idbodega = $det['idbodega'];       
                $detalle->save();
            }            
            DB::commit();
        } catch (Exception $e){
                DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $User = User::findOrFail($request->id);
        $User->condicion = '0';
        $User->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

}
