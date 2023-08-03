<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class loginController extends Controller
{
    public function create(){
        return view('login');
    }
    public function store(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);

        $credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
            // 'status'=>'active'

        ];
       $result=Auth::attempt(
            $credentials,
        $request->boolean('remember')
       );


        if($result){
            return redirect()->intended('/');
        }
            return back()->withInput()->withErrors([
                'email'=>'Invalid cerdentials'
            ]);


    }



}
