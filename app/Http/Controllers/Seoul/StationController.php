<?php

namespace App\Http\Controllers\Seoul;

use Illuminate\Support\Facades\Response;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Http\Traits\ReturnHelper;

class StationController extends Controller
{
	use ReturnHelper;
	
    private $key;
    protected $end_point;

    public function __construct()
    {
        $this->key = config('key');
        $this->end_point = "http://ws.bus.go.kr/api/rest/stationinfo";
        $this->client = new Client();
    }
    
    public function getStationInfo($station_name=null)
    {
        $list = array();
        $response = (string)$this->client->request('get', $this->end_point.'/getStationByName?serviceKey='.$this->key.'&stSrch='.$station_name)->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }        
        
        return $this->returnJson($list);
    }

    public function getStationBusList($arsId=null)
    {
        $list = array();
        $response = (string)$this->client->request('get', $this->end_point.'/getRouteByStation?serviceKey='.$this->key.'&arsId='.$arsId)->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }        

        return $this->returnJson($list);
    }

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
        
        return $this->returnKeyJson($key, $list);
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
