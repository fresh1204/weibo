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
    /*
    public function feed(){
        return $this->statuses()->orderBy('created_at','desc');
    }
    */

    //获取所有关注用户的微博动态及当前用户的微博动态
    public function feed(){
        //取出所有关注用户的id放到数组中
        $user_ids = $this->followings->pluck('id')->toArray();
        //将当前用户的 id 加入到 user_ids 数组中；
        array_push($user_ids, $this->id);
        //return 'hello';exit;
        return Status::whereIn('user_id',$user_ids)->with('user')->orderBy('created_at','desc');
    }


    //获取粉丝列表
    public function followers(){
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    //获取用户关注人列表
    public function followings(){
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    //关注用户
    public function follow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    //取消关注
    public function unfollow($user_ids){
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    //判断当前登录的用户是否关注了用户 B
    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }

}
