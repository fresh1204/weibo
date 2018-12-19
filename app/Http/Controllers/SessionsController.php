<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    //登录表单
    public function create(){
    	return view('sessions.create');
    }

    //登录处理
    public function store(Request $request){
    	$credentials = $this->validate($request,[
    		'email'=>'required|email|max:255',
    		'password'=>'required',
    	]);
    	//var_dump(Auth::user());exit;
    	//数据库验证
    	if(Auth::attempt($credentials)){
    		session()->flash('success','欢迎回来！');
    		return redirect()->route('users.show',[Auth::user()]);
    	}else{
    		session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
    		return redirect()->back()->withInput();
    	}
    	return;
    }

    //用户退出
    public function destroy(){
    	Auth::logout();
    	session()->flash('success', '您已成功退出！');
    	return redirect('login');
    }
}
