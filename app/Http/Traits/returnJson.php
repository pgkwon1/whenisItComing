<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Response;

trait ReturnJson 
{
	/*
	 *   @var array
	 */
    protected function returnJson($list)
    {
        if (sizeof($list) ) {
	        return Response::json(['status'=>'success', 'data'=>$list]);
        } else {
	        return Response::json(['status'=>'fail']);
        }
    } 
}