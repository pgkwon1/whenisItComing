<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Response;

trait ReturnHelper 
{
    /*
     *   @var array
     */
    protected function returnJson(Array $list)
    {
        if (sizeof($list) ) {
	        return Response::json(['status'=>'success', 'data'=>$list]);
        } else {
	        return Response::json(['status'=>'fail']);
        }
    }

    protected function returnBoolean(Bool $result)
    {
        if ($result) {
            return Response::json(['status'=>'success']);
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