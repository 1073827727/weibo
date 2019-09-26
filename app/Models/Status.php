<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $fillable = ['content'];
    //fillable  属性中允许更新微博的  content  字段

    public function user(){
        //指名一条微博指属于一个用户
        return $this->belongsTo(User::class);

    }


}
