<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Auth;

class SessionController extends Controller
{
    public function create()
    {
        return view('session.create');
    }
    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        if(Auth::attempt($credentials)){
            //登陆成功后
            session()->flash('success','欢迎');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            //失败操作
            session()->flash('danger','抱歉，登陆失败');
            return redirect()->back()->withInput();
        }
    }
}
