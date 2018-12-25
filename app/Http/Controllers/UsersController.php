<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use Mail;
use Auth;
class UsersController extends Controller
{
	public function __construct(){
		//开启未登录用户访问权限
		$this->middleware('auth', [            
            'except' => ['show', 'create', 'store','index','confirmEmail']
        ]);

        //只让未登录用户访问注册页面
        $this->middleware('guest',[
        	'only'=>['create']
        ]);
	}

	//显示所有用户列表
	public function index(){
		/*
		echo count(User::first()->followers);
		echo '<br/>';
		echo User::first()->followers()->count();
		exit;
		*/

		//获取所有用户
		//$users = User::all();
		//分页获取用户列表
		$users = User::paginate(10);
		return view('users.index',compact('users'));
	}

    //注册页面表单
    public function create(){
    	return view('users.create');
    }

    //显示用户信息(加微博信息)
    public function show(User $user){
    	 //获取用户微博分页列表
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(10);

    	//return view('users.show',compact('user'));
    	return view('users.show',compact('user','statuses'));
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

    	//邮箱发送激活
    	$this->sendEmailConfirmationTo($user);
    	session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
    	return redirect('/');
    	/*
    	//用户自动登录
    	Auth::login($user);

    	session()->flash('success', '欢迎'.$user->name.'，您将在这里开启一段新的旅程~');

    	return redirect()->route('users.show',[$user]);
    	*/
    }

    //编辑用户表单
    public function edit(User $user){
    	//授权校验
    	$this->authorize('update',$user);
    	return view('users.edit',compact('user'));
    }

    //对用户更新进行处理
    public function update(User $user,Request $request){
    	//授权校验
    	$this->authorize('update',$user);
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

    //对用户进行删除
    public function destroy(User $user){
    	//授权删除操作校验
    	$this->authorize('destroy',$user);

    	$user->delete();
    	session()->flash('success', '成功删除用户'.$user->name.'！');

    	return back();
    }

    //注册成功后发送邮件确认
    protected function sendEmailConfirmationTo($user){
    	//$from = '1942480291@qq.com';
    	//$name = '原上草';

    	$subject = "感谢注册 Weibo 应用！请确认你的邮箱。";
    	$data = compact('user');
    	$to = $user->email;
    	$view = 'emails.confirm';

    	/*
    	Mail::send($view,$data,function($message) use ($from,$name,$to,$subject){
    		$message->from($from,$name)->to($to)->subject($subject);
    	});
		*/

    	Mail::send($view,$data,function($message) use ($to,$subject){
    		$message->to($to)->subject($subject);
    	});
    }

    //激活用户
    public function confirmEmail($token){
    	$user = User::where('activation_token',$token)->firstOrFail();
    	$user->activated  = true;
    	$user->activation_token = null;
    	$user->save();

    	Auth::login($user);
    	session()->flash('success', '恭喜你，激活成功！');
    	return redirect()->route('users.show',[$user]);
    }

    //用户关注人列表
    public function followings(User $user){
    	$users = $user->followings()->paginate(15);
    	$title = $user->name . "关注的人";

    	return view('users.show_follow',compact('users','title'));
    }

    //用户粉丝列表
    public function followers(User $user){
    	$users = $user->followers()->paginate(15);
    	$title = $user->name . '的粉丝';

    	return view('users.show_follow',compact('users','title'));
    }


}
