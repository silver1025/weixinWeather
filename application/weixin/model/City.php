<?php
namespace app\weixin\model;

use think\Model;
use think\Db;

class City extends Model
{
  public function get_citycode($city_name ="北京")
  {
    $res = Db::name('ins_county')->where('county_name',$city_name)->select();
    return $res[0]['weather_code'];
  }
}