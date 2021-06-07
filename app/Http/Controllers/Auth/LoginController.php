<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Control;
use App\Idbodega;
use App\Bodega;

class LoginController extends Controller
{

    public function showLoginForm(){
        return view('auth.login');
    }

    public function login(Request $request){
        $this->validateLogin($request);   
        
        $idbodega=$request->idbodega;

        $idusuario1=User::where('usuario','=',$request->usuario)
        ->select('id')->get();

        foreach($idusuario1 as $id)
        {
            $idusuario=$id['id'];
        }

        $contadorusuario=count($idusuario1);

        if($contadorusuario>=1)
        {
            $id = Control::where('idbodega','=',$idbodega)
            ->where('idusuario', '=',$idusuario)
            ->select('id')->get();

            if(count($id)!=0)
            {
                if (Auth::attempt(['usuario' => $request->usuario,'password' => $request->password,'condicion'=>1])){
                    $bodegaactual= new Idbodega();
                    $bodegaactual->idbodega=$idbodega;
                    $bodegaactual->idusuario=$idusuario;
                    $bodegaactual->save();
                    
                    return redirect()->route('main');             
                }
                return back()
                ->withErrors(['usuario' => trans('auth.failed')])
                ->withInput(request(['usuario']));
            }   
            else{
                return back()
                ->withErrors(['usuario' => trans('auth.failed')])
                ->withInput(request(['usuario']));
            }
        }
        else{
            return back()
            ->withErrors(['usuario' => trans('auth.failed')])
            ->withInput(request(['usuario']));
        }
    }

    protected function validateLogin(Request $request){
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);

    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }

    public function obtenerlogin(Request $request){        
        $idusuario = Auth()->user()->id;
        $usuario = Auth()->user()->usuario;

        $idbodega1 = Idbodega::where('idusuario','=',$idusuario)
        ->select('idbodega')
        ->orderBy('id','desc')->take(1)->get();
        foreach($idbodega1 as $id)
        {
            $idbodega=$id['idbodega'];
        }

        $bodega1 = Bodega::where('id','=',$idbodega)
        ->select('nombre','alias','cantidad')->get();
        foreach($bodega1 as $b1)
        {
            $bodega=$b1['nombre'];
            $cantidad=$b1['cantidad'];
            $alias=$b1['alias'];
        }

        return ['idusuario'=>$idusuario,'usuario'=>$usuario, 'idbodega'=>$idbodega,'bodega'=>$bodega,'cantidad'=>$cantidad,'alias'=>$alias];    
    
    }
}