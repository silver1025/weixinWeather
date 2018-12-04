<?php
namespace app\api\controller;

use think\Controller;

class City extends Controller
{
  	public function read()
    {
      $city_name = input('city_name');
      $model = model('City');
      $data=$model->get_citycode($city_name);
      if($data){
      	$code=200;
      }else{
        $code=404;
      }
      $data= [
      'code'=>$code,
      'weather_code'=>$data
      ];
      return json($data);
      }
}