<?php

namespace App\models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($user){
            $user->activation_token =Str::random(10);
        });

    }

    public function statuses(){
        //指明每个用户可对应多个微博
        return $this->hasMany(status::class);
    }





    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //头像
    public function gravater($size='100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function feed()
    {
        $user_ids = $this->followings ->pluck('id')->toArray();
        //followings  方法取出所有关注用户的信息，再借助  pluck  方法将  id  进行分离并赋值给  user_ids
        array_push($user_ids,$this->id);
        //whereIn  方法取出所有用户的微博动态并进行倒序排序,with  方法，预加载避免了  N+1 查找的问题 ，提查询效率
        return status::whereIn('user_id',$user_ids)->with('user')->orderBy('created_at','desc');


       // return $this->statuses()
             //       ->orderBy('created_at', 'desc');
    }


    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    public function follow($user_ids)
    {
        if(!is_array($user_ids)){
            //判断是否是数组
            $user_ids = compact('user_ids');
            //不是数组通过compact转数组
        }
        $this->followings()->sync($user_ids,false);
        //增加关注
    }

    public function unfollow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_Ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
        //删除关注的数据
    }


    public function isFollowing($user_id)
    {
        //判断用户是否在关注者的关注列表里
        return $this->followings->contains($user_id);
    }

}
