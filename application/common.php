<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

    function transmitNews($object, $arr_item)  //消息转换成图文或者文本消息函数
	{
	    if(!is_array($arr_item))//判断传入值是数组还是字符串，如果是字符串则生成text文本类型xml消息，如果是数组则组成news图文类型xml
	    {
	        $type = "text";  //定义好消息类型：文本
	        $textTpl = "
	            <Content><![CDATA[%s]]></Content>";
	        $item_str = sprintf($textTpl, $arr_item);//相当于<Content><![CDATA[text]]></Content>，把$type写入到$textTpl的占位符%s那里
	    }else
	    {
	        $type ="news";//定义好消息类型：图文
	        $itemTpl = "<item>
	                    <Title><![CDATA[%s]]></Title>
	                    <Description><![CDATA[%s]]></Description>
	                    <PicUrl><![CDATA[%s]]></PicUrl>
	                    <Url><![CDATA[%s]]></Url>
	                </item>
	            ";


	         


	        $item_str = "";
	        foreach ($arr_item as $item)
	        {
	            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
	        }
	        $newsTpl = "<Content><![CDATA[]]></Content>
	                        <ArticleCount>%s</ArticleCount>
	                        <Articles>$item_str</Articles>
	                         ";
	        $item_str = sprintf($newsTpl, count($arr_item));
	    }
	    
	    $Tpl = "<xml>
	        <ToUserName><![CDATA[%s]]></ToUserName>
	        <FromUserName><![CDATA[%s]]></FromUserName>
	        <CreateTime>%s</CreateTime>
	        <MsgType><![CDATA[%s]]></MsgType>
	        $item_str
	        </xml>";
	    
	    $result = sprintf($Tpl, $object->FromUserName, $object->ToUserName, time(), $type);
	    return $result;
	    
	}



//https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx7d296dbb34b99771&secret=858ee4437a5f76f71a717674ba9978ac;
function get_access_token($appID,$appsecret,$token){
		static $access_token;
			$access_token = S($token.'weixin_access_token');
			if($access_token) { //已缓存，直接使用
			return $access_token;
			
		} else { //获取access_token
			$url_get =
			'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='
			. $appID . '&secret=' . $appsecret;
			$ch1 = curl_init ();
			$timeout = 5;
			curl_setopt ( $ch1, CURLOPT_URL, $url_get );
			curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
			curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
			$accesstxt = curl_exec ( $ch1 );
			curl_close($ch1 );
			$access = json_decode ( $accesstxt, true );
			// 缓存数据7200秒
			S($token.'weixin_access_token',$access['access_token'],7200);
			return $access['access_token'];
			
			}
		}

//https://api.weixin.qq.com/cgi-bin/user/info?access_token=9_3IMHKH6r8NLH9q6inRjOoXGycy0dTwYUrau-AOMpGVLCSIdQpxtND0xfL0L42aXlA_PNdAtLd1zmhpwUIIsdUNT07-kK1G4ePUktNrbN2CPPpLin_jeMkTPHTchusHwiKP98wH7Tg1SNAwWdCHNaAHAPUA&openid=oKBWjv0S2OFL7-uk5zKeUjbtanj0


function get_userinfo($access_token,$openid)
	  {
	    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}";  
	    $ch = curl_init();  
	    curl_setopt($ch,CURLOPT_URL,$url);  
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);  
	    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);  
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
	    $output = curl_exec($ch);  
	    curl_close($ch);  
	    $jsoninfo = json_decode($output,true);  
	    $nickname = $jsoninfo["nickname"];  
	    $headimgurl= $jsoninfo["headimgurl"];  
	    $userinfo=array('nickname'=>$nickname,'headimgurl'=>$headimgurl);
	    return $userinfo;
	  }



	  function mmpy($score)        //评语
	  {
	  	if($score<=30&&$score>=0)
        {
            $content="有点水！才".$score."分";
        }
        if($score<=60&&$score>30)
        {
            $content="还行吧！有".$score."分";
        }
        if($score<=80&&$score>60)
        {
            $content="厉害吼！竟".$score."分";
        }
         if($score<=100&&$score>80)
        {
            $content="我什么时候才能像你这么优秀，".$score."分大佬";
        }
        return $content;
	  }