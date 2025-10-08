<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\RegisterTokens;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }

    public function dologin(Request $request){
        $email =$request->email;
        $password = $request->password;

        if(Auth::attempt(["email"=>$email,"password"=>$password])){
            return redirect()->route('home');

        }else{
            return back()->with('error','Credenciales invalidas');
        
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function register(Request $request){

        $tokenv = RegisterTokens::where('token',$request->token)->first();

        if(!$request->token || !$tokenv){
            return redirect()->route('login')->with('error','Token invalido');
        }

        if($tokenv->status){
            return redirect()->route('login')->with('error','El token ya fue utilizado');
        }

        $token = $tokenv->token;
        $email = $tokenv->email;


        return view('register')->with(compact('token','email'));
    }

    public function doRegister(Request $request){
        $token = $request->token;
        $name = $request->name;
        $last_name = $request->last_name;
        $full_name = $name." ".$last_name;
        $password = $request->password;
        $cpassword = $request->cpassword;

        if($password != $cpassword){
            return back()->with('error','Las contraseñas no coinciden');
        }

        if(strlen($password) < 5){
            return back()->with('error', 'La contraseña debe tener por lo menos 5 caracteres');
        }

       

        $token = RegisterTokens::where('token',$token)->first();

        $v = User::where('email',$token->email)->first();

        if($v){
            return back()->with('error','Este correo electronico ya está en uso, intenta usando otro');
        }

        User::create([
            "name"=> $full_name,
            "email" => $token->email,
            "password" => bcrypt($password)
        ]);

        RegisterTokens::where('token',$token->token)->update([
            "status" => 1
        ]);

        return redirect()->route('login')->with('success','Te has registrado satisfactoriamente');
    }
}

