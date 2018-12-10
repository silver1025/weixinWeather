<?php
namespace app\api\controller;

use think\Controller;

class Weather extends Controller
{
  	public function read()
    {
      $citycode = input('citycode');
      $model = model('Weather');
      $data=$model->get_weather($citycode);
      if($data){
      	$code=200;
      }else{
        $code=404;
      }
      $data= [
      'code'=>$code,
      'weatherInfo'=>$data
      ];
      return json($data);
      }
}
?>