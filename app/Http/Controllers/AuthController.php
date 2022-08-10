<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name"=>'required',
            "email"=>'required|email|unique:users',
            "password"=>'required|min:4|max:12'
        ]);

        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $result=$user->save();
        if ($result) {
            return back()->with('success','register successfully');
        }
        else {
            return back()->with('fail','somthing wrong');
        }

    }

    public function submitlogin(Request $request){
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|min:4|max:12'
        ]);

        $user=User::where('email','=',$request->email)->first();
        if ($user) {
            if (Hash::check($request->password,$user->password)) {
                $request->session()->put('loginId',$user->id);
                return redirect('/list');
            }else {
                return back()->with('fail','password is wrong');
            }
        }else {
            return back()->with('fail','email is not registered');
        }
    }

    public function logout(){
        if (session::has('loginId')) {
            session::pull('loginId');
            return redirect('/');
        }
    }
}
