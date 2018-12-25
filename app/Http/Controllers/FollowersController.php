<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class FollowersController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
    }

    //关注
    public function store(User $user){
    	//对用户身份进行授权判断
    	$this->authorize('follow',$user);

    	if(!Auth::user()->isFollowing($user->id)){
    		Auth::user()->follow($user->id);
    	}

    	return redirect()->route('users.show',$user->id);
    }

    //取消关注
    public function destroy(User $user){
    	//对用户身份进行授权判断
    	$this->authorize('follow',$user);

    	if(Auth::user()->isFollowing($user->id)){
    		Auth::user()->unfollow($user->id);
    	}

    	return redirect()->route('users.show',$user->id);
    }
}
