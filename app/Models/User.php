<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
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

    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //在用户模型类完成初始化之后进行加载
    public static function boot(){
        parent::boot();
        //用于监听模型被创建之前的事件
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    //指明一个用户拥有多条微博
    public function statuses(){
        return $this->hasMany(status::class);
    }

    //获取当前用户发布过的所有微博
    public function feed(){
        return $this->statuses()->orderBy('created_at','desc');
    }
}
