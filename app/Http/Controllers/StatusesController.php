<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Status;
class StatusesController extends Controller
{
    //
    public function __construct(){
    	$this->middleware('auth');
    }

    //发布微博保存
    public function store(Request $request){
    	$this->validate($request,[
    		'content' => 'required|max:140'
    	]);

    	Auth::user()->statuses()->create([
    		'content' => $request['content']
    	]);

    	session()->flash('success','发布成功');

    	return redirect()->back();
    }

    //删除微博
    public function destroy(Status $status){
    	//做删除授权的检测
    	$this->authorize('destroy',$status);
    	$status->delete();
    	session()->flash('success','微博已被成功删除!');

    	return redirect()->back();
    }
}
