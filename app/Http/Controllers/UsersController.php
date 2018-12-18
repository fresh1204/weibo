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

    public function show(User $user){
    	return view('users.show',compact('user'));
    }
}
