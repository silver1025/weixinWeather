<?php
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
				$weatherinfo = sprintf($weather, $cityName, $data, $time, $humidity, $pmData,
								$pmQuality, $low, $high, $nowTemperature, $climate, $wind);  
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $weatherinfo);
                echo $info;
			}
		}

function http_get($url){
       $ch = curl_init ();
      curl_setopt ( $ch, CURLOPT_URL, $url );
      curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
      curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
      curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
      $data = curl_exec ( $ch );
      curl_close ( $ch );
      return $data;
    }
?>