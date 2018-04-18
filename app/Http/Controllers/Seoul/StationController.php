<?php

namespace App\Http\Controllers\Seoul;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Alarm;

class StationController extends Controller
{
    private $key;
    protected $end_point;

    public function __construct(Alarm $alarm)
    {
        $this->key = config('key');
        $this->end_point = "http://ws.bus.go.kr/api/rest/stationinfo";
        $this->client = new Client();
    }

    /*
     * 정류소 정보 가져오기
     */

    public function getStationInfo($station_name=null)
    {
        $list = array();
        $response = (string)$this->client->request('get', $this->end_point.'/getStationByName?serviceKey='.$this->key.'&stSrch='.$station_name)->getBody();
        echo $this->end_point.'/getStationByName?serviceKey='.$this->key.'&stSrch='.$station_name;
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }        
        if (sizeof($list) ) {
		   return Response::json(['status'=>'success', 'data'=>$list]);
        } else {
	       return Response::json(['status'=>'fail']);
        }
    }

    public function getStationBusList($arsId=null)
    {
        $list = array();
        $response = (string)$this->client->request('get', $this->end_point.'/getRouteByStation?serviceKey='.$this->key.'&arsId='.$arsId)->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }        

        if (sizeof($list) ) {
		   return Response::json(['status'=>'success', 'data'=>$list]);
        } else {
	       return Response::json(['status'=>'fail']);
        }
    }

    /*
     * 정류소 버스 도착 정보 가져오기
     *  arsId from db (int)
     */

    public function getStationArrivalInfo($arsId = null, $bus = null)
    {
        $response = (string)$this->client
                    ->request('get', $this->end_point.'/getStationByUid?serviceKey='.$this->key.'&arsId='.$arsId)
                    ->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }
        $key = array_search($bus, array_column($list, 'rtNm'));
        if (is_int($key)) {
            return Response::json(['status'=>'success', 'data'=>$list[$key]]);
        } else {
	        return Response::json(['status'=>'fail']);
        }
    }

    public function getCongestion($bus_route_id = null, $start_ord = null, $end_ord = null)
    {
        $this->end_point = 'http://ws.bus.go.kr/api/rest/buspos/getBusPosByRouteSt';
        $response = (string) $this->client
                     ->request('get', $this->end_point.'/?busRouteId='.$bus_route_id.'&startOrd='.$start_ord.'&endOrg='.$end_ord)
                     ->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }
    }
}
