<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class StaticPagesController extends Controller
{
    //
    public function home()
    {
        //获取用户微博动态(调用用户模型中定义的feed方法)
        $feed_items = [];
        if(Auth::check()){
            $feed_items = Auth::user()->feed()->paginate(15);
            //echo '<pre>';print_r($feed_items->toArray());exit;
        }
    	return view('static_pages/home',compact('feed_items'));
    }

    public function help(){
    	return view('static_pages/help');
    }

    public function about(){
    	return view('static_pages/about');
    }
}
