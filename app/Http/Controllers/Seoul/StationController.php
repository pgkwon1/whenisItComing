<?php

namespace App\Http\Controllers\Seoul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Alarm;

class StationController extends Controller
{
    protected $key;
    protected $end_point;

    public function __construct()
    {
        $this->key = "o15O9nSSs%2FE5zVouFesgBgDFsN9RWWmuN%2FXLqvcEh14m4NR1EuC2h8e0puNvtaK3%2FI%2Bd07fNaoLpaBIrX9NtcQ%3D%3D";
        $this->end_point = "http://ws.bus.go.kr/api/rest/stationinfo";
        $this->client = new Client();
    }
	
	/*
	 * 정류소 정보 가져오기
	 */
	 
    public function getStationInfo($station_name = null) 
    {
        $list = array();
        $response = (string)$this->client->request('get', $this->end_point.'/getStationByName?serviceKey='.$this->key.'&stSrch='.$station_name)->getBody();
        $response = simplexml_load_string($response);
        foreach ($response->msgBody->itemList as $key => $val) {
            $list[] = (array)$val;
        }
        $this->getStationArrivalInfo($list[0]['arsId'],"화곡동 대림아파트");
        return json_encode($list);
    }
    
	/*
	 * 정류소 버스 도착 정보 가져오기
	 *  arsId from db (int)
	 */
	 
	public function getStationArrivalInfo($arsId=null, $bus=null)
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
			print_r($list[$key]);		
		}
		return $list;
	}
}
