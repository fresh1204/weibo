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

    	//往数据库中插入数据
    	$user = User::create([
    		'name'=>$request->name,
    		'email'=>$request->email,
    		'password'=>bcrypt($request->password),
    	]);

    	//用户自动登录
    	Auth::login($user);

    	session()->flash('success', '欢迎'.$user->name.'，您将在这里开启一段新的旅程~');

    	return redirect()->route('users.show',[$user]);
    }

    //编辑用户表单
    public function edit(User $user){
    	return view('users.edit',compact('user'));
    }

    //对用户更新进行处理
    public function update(User $user,Request $request){
    	//表单数据校验
    	$this->validate($request,[
    		'name'=>'required|max:50',
    		'password'=>'nullable|confirmed|min:6',
    	]);

    	/*
    	$user->update([
    		'name'=>$request->name,
    		'password'=>bcrypt($request->password),
    	]);
    	*/
    	//优化
    	$data = [];
    	$data['name'] = $request->name;
    	if($request->password){
    		$data['password'] = $request->password;
    	}
    	$user->update($data);
    	session()->flash('success', '个人资料更新成功！');

    	return redirect()->route('users.show',$user->id);
    }


}
