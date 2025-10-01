<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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
}

