<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{
    public function __construct(){
        //判断用户是否登录
        $this->middleware('auth');
    }


    public function store(User $user){
        //对用户进行授权，没关注才可以使用增加关注功能
        $this->authorize('follow',$user);
        if( !Auth::user()->isFollowing($user->id)){

            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.show', $user->id);

    }

    public function destroy(User $user){
        $this->authorize('follow',$user);
        if(Auth::user()->isFollowing($user->id)){
            Auth::user()->unfollow($user->id);
        }
        return redirect()->route('users.show',$user->id);
    }














}
