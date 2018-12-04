<?php
namespace app\weixin\controller;
class Weixin{
  
public function oauth(){
if (isset($_GET['code'])){
    echo $_GET['code'];
}else{
    echo "NO CODE";
}
}
  
public function wx(){
//1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;   
			}
		}
		
		//判断是否为文本消息
		if( strtolower( $postObj->MsgType) == 'text'){
            //接受文本消息
            $content = $postObj->Content;
			$cityName = strstr($content, "天气", true);
			if(empty($cityName)){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '您发送的内容是：'.$content;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info; 
			}else{
				//回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
				$time = "10:24";
				$humidity = "57%";
				$pmData = "25";
				$pmQuality = "优";
				$data = "11月24日星期六";
				$high = "10";
				$low = "-3";
				$nowTemperature = "4";
				$climate = "晴";
				$wind = "微风";
                $weather  = 
"%s今日天气：
日期：%s
发布时间：%s
湿度：%s
pm2.5：%s
空气质量：%s
温度：%s℃-%s℃
实时温度：%s℃
天气：%s
风力：%s";
              	$test = $this->http_get("http://t.weather.sojson.com/api/weather/city/101030100");
				$weatherinfo = sprintf($weather, $cityName, $data, $time, $humidity, $pmData,
								$pmQuality, $low, $high, $nowTemperature, $climate, $wind);  
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $test);
                echo $info;
			}
		}	
}
  
public function index()
    {
        $access_token = $this->getToken();
        echo $access_token;
    }



    function getToken(){
        return $this->checkAccessToken("wxb6f59701f277eaff","dea11a13d0c4887725042940d4422db9");
    }



    function checkAccessToken($appid,$appsecret){
        $condition = array('appid'=>$appid,'appsecret'=>$appsecret);
        $access_token_set=DB('wxtoken')->where($condition)->find();//获取数据

        if($access_token_set){
            //检查是否超时，超时了重新获取
            if($access_token_set['AccessExpires']>time()){
                //未超时，直接返回access_token
                return $access_token_set['access_token'];
            }else{
                //已超时，重新获取
                $url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
                $json= $this->https_request($url_get);
                var_dump($json);
                $access_token=$json['access_token'];
                $AccessExpires=time()+intval($json['expires_in']);
                $data['access_token']=$access_token;
                $data['AccessExpires']=$AccessExpires;
                $result = DB('wxtoken')->where($condition)->update($data);//更新数据
                if($result){
                    return $access_token;
                }else{
                    return $access_token;
                }
            }
        }else{
          	echo "appid或appsecret不正确";
            return false;
        }
    }


    function https_request ($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        curl_close($ch);
        return  json_decode($out,true);
    }
  
  function http_get($url){
       $ch = curl_init ();
      curl_setopt ( $ch, CURLOPT_URL, $url );
      curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
      curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
      curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
      $data = curl_exec ( $ch );
      curl_close ( $ch );
      //return json_decode($data,true);
    	return $data;
    }
  
  
      public function createmenu(){
      $data='{
       "button":[
       {
            "type":"view",
            "name":"今日天气",
            "url":"http://123.207.170.21/wx/jQueryweather.html"
        },
        {
             "name":"菜单",
             "sub_button":[
             {
                 "type":"view",
                 "name":"搜索",
                 "url":"http://www.soso.com/"
              },
              {
                 "type":"click",
                 "name":"赞一下我们",
                 "key":"V1001_GOOD"
              }]
         }]
      }';
      $access_token=$this->getToken();
      $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
      var_dump($url);
      var_dump($data);
      $result= $this->postcurl($url,$data);
      var_dump($result);
    }


    function postcurl($url,$data = null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return 	$output=json_decode($output,true);
    }
  
     public function getuser(){
    	 $access_token=$this->getToken();
   		 $url_get='https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token;
         $user_json= $this->https_request($url_get);
         var_dump($user_json);
    	 $url_get='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$user_json['data']['openid'][0].'&lang=zh_CN';
    	 $user_info= $this->https_request($url_get);
         $img_url=$user_info["headimgurl"];
         var_dump($user_info);
         $template= '<img border="0" src="%s" width="100" height="100">';
         $img= sprintf($template, $img_url);
         echo $img;
  }
}
?>