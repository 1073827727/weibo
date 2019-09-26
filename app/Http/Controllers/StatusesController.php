<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;


class StatusesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        //通过中间件过滤没登录的用户
    }

    public function store(Request $request){
        $this->validate($request,[
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success','发布成功');
        return redirect()->back();

    }

    public function destroy(status $status){
        $this->authorize('destroy',$status);
        //删除授权的检测，不通过会抛出 403 异常
        $status->delete();
        //调用 Eloquent 模型的  delete  方法对该微博进行删除
        session()->flash('success','微博已被成功删除');
        return redirect()->back();

    }














}
