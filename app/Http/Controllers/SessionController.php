<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Auth;

class SessionController extends Controller
{
    public function __construct(){
        //只让未登录用户访问登陆页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);

    }

    public function create()
    {
        return view('session.create');
    }

    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        if(Auth::attempt($credentials,$request->has('remember'))){
            //判断是否激活
            if(Auth::user()->activated){
            //登陆成功后
                session()->flash('success','欢迎');
                $fallback = route('users.show',[Auth::user()]);
                return redirect()->intended($fallback);
            }else{
                //未激活
                Auth::logout();
                session()->flash('warning','您的账号未激活请检查邮箱中的注册邮箱激活');
                return redirect('/');
            }
        }else{
                //失败操作
                session()->flash('danger','抱歉，登陆失败');
                return redirect()->back()->withInput();
        }

    }


    public function destory(){
        Auth::logout();
        session()->flash('success','成功推出');
        return redirect('login');

    }




}
