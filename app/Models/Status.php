<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    public function user(){
        //指名一条微博指属于一个用户
        return $this->belongsTo(User::class);

    }


}
