<?php
namespace app\api\model;

use think\Model;
use think\Db;

class Weather extends Model
{
  public function get_weather($citycode =101010100)
  {
    $res = Db::name('news')->where('id',$id)->select();
    return $res;
  }
}