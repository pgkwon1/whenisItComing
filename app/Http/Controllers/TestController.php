<?php
	
namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
	public $serviceKey;
	
	public function __construct() {
		
		$this->serviceKey = 'TsUBH9UKyi4Qs40M%2FMLi3EIcFRbiNqbmHgCMPhybceO5IAvCjBE%2BEQDaBmvcCxMObsyFLog3ai9rka28CvtbuQ%3D%3D';
		$this->endPoint = "http://ws.bus.go.kr/api/rest/stationinfo/";			
	}
	
	public function getStationInfo($station_name)
	{
		
		$client = new Client();
		$result = $client->request('get',$this->endPoint.'/getStationByName?serviceKey='.$this->serviceKey.'&stSrch='.$station_name);
		$body=(string) $result->getBody();
		print_r($body);
	}

	public function dbCheck($token="1234")
	{
		$res = DB::table('token')
			->select('*')
			->where('token',$token)
			->first();
			
		print_r($res);
	}	
}