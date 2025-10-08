<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RegisterTokens;
use Auth;

class UsersController extends Controller
{
    public function index(){
        $users = User::get();
        $tokens = RegisterTokens::get();
        return view('users')->with(compact('users','tokens'));
    }





    // Tokens
    public function crearToken(Request $request){
        $email = $request->email;

        $v = RegisterTokens::where('email',$email)->first();

        if($v){
            return back()->with('error','Ya has creado un token para ese correo');
        }

        RegisterTokens::create([
            "email" => $request->email,
            "nombre" => $request->nombre,
            "token" => md5(date("Y-m-d H:i:s").rand(0,9999999)."sinstituciones".rand(0,1000).uniqid()),
            "created_by" => Auth::user()->id,
        ]);

        return back()->with('success','Se ha creado el token de registro satisfactoriamente');
    }
}
