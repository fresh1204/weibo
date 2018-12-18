<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
class UsersController extends Controller
{
    //注册页面表单
    public function create(){
    	return view('users.create');
    }

    //显示用户信息
    public function show(User $user){
    	return view('users.show',compact('user'));
    }

    //对注册表单进行处理
    public function store(Request $request){
    	$this->validate($request,[
    		'name'=>'required|max:50',
    		'email'=>'required|email|unique:users|max:255',
    		'password'=>'required|confirmed|min:6'
    	]);
    	return;
    }
}
