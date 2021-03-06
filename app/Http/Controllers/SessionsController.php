<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    public function __construct(){
        //只让未登录用户访问登录页面
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }


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
    	if(Auth::attempt($credentials,$request->has('remember'))){
            //判断是否已激活
            if(Auth::user()->activated){
        		session()->flash('success','欢迎回来！');
                $fallback = route('users.show',Auth::user());
                return redirect()->intended($fallback);
        		//return redirect()->route('users.show',[Auth::user()]);
            }else{
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
    	}else{
    		session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
    		return redirect()->back()->withInput(); //作用是将旧表单值保存到 Session 中。在新页面你可以使用old获取这些数据。
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
