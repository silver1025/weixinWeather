<?php
namespace app\api\controller;

use think\Controller;

class Weather extends Controller
{
  	public function read()
    {
      $citycode = input('citycode');
      $model = model('Weather');
	  $url="http://t.weather.sojson.com/api/weather/city/";
	  $url = $url.$citycode;
	  $data =$this-> http_get($url);
      if($data){
      	$code=200;
      }else{
        $code=404;
      }
      $data= [
      'code'=>$code,
      'data'=>$data
      ];
      return json($data);
    }
  
	private function http_get($url){
       $ch = curl_init ();
      curl_setopt ( $ch, CURLOPT_URL, $url );
      curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
      curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
      curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
      $data = curl_exec ( $ch );
      curl_close ( $ch );
      return $data;
    }
}
?>