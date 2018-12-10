<?php
namespace app\weixin\model;

use think\Model;
use think\Db;

class Weather extends Model
{
  public function get_weather($citycode = 101010100)
  {
    $res = Db::name('weixin_weather')->where('citycode',$citycode)->select();
    return $res;
  }
}