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

    public function getStationArrivalInfo($arsId = null)
    {
        $response = (string)$this->client
                    ->request('get', $this->end_point.'/getStationByUid?serviceKey='.$this->key.'&arsId='.$arsId)
                    ->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }
        return $this->returnJson($list);
    }

    public function getArrivalInfo($arsId = null, $bus = null)
    {
        $response = (string)$this->client
            ->request('get', $this->end_point.'/getStationByUid?serviceKey='.$this->key.'&arsId='.$arsId)
            ->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }
        $key = array_search($bus, array_column($list, 'rtNm'));
        $list[$key]['congetion'] = $this->getCongestion($list[$key]['busRouteId'], $list[$key]['sectOrd1']-1, $list[$key]['staOrd']);
        return $this->returnKeyJson($key,$list);
    }

    public function getCongestion($bus_route_id = null, $start_ord = null, $end_ord = null)
    {
        $this->end_point = 'http://ws.bus.go.kr/api/rest/buspos/getBusPosByRouteSt';
        $response = (string) $this->client
                     ->request('GET', $this->end_point.'?serviceKey='.$this->key.'&busRouteId='.$bus_route_id.'&startOrd='.$start_ord.'&endOrd='.$end_ord)
                     ->getBody();
        $response = simplexml_load_string($response);
        if (sizeof($response->msgBody->itemList)) {
            foreach ($response->msgBody->itemList as $key => $val) {
                $list[0] = (array)$val;
            }
            if (!$list[0]['congetion']) {
                return "0";
            } else {
                return $list[0]['congetion'];
            }
        } else {
            return false;
        }
    }
}
