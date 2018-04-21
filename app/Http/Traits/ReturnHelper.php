<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Response;

trait ReturnHelper 
{
	/*
	 *   @var array
	 */
    protected function returnListJson(Array $list)
    {
        if (sizeof($list) ) {
	        return Response::json(['status'=>'success', 'data'=>$list]);
        } else {
	        return Response::json(['status'=>'fail']);
        }
    }
    
    /*
	 *   @var Int
	 */
    protected function returnKeyJson(Int $key, Array $list)
    {
        if (is_int($key)) {
            return Response::json(['status'=>'success', 'data'=>$list[$key]]);
        } else {
	        return Response::json(['status'=>'fail']);
        }
    }
}