<?php 
class HttpRequest{
	/**
	 * POST 请求
	 * @param  $url	请求地址
	 * @param  $params	请求参数数组
	 * @param  $header	HTTP头信息
	 */
	 function post($url, $data = array(), $timeout = 30, $CA = false ){

		$cacert = getcwd() . '/cacert.pem'; //CA根证书  
	    $SSL = substr($url, 0, 8) == "https://" ? true : false;  
	      
	    $ch = curl_init();  
	    curl_setopt($ch, CURLOPT_URL, $url);  
	    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);  
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);  
	    if ($SSL && $CA) {  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书  
	        curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配  
	    } else if ($SSL && !$CA) {  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名  
	    }  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题  
	    curl_setopt($ch, CURLOPT_POST, true);  
	    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //data with URLEncode  
	  
	    $ret = curl_exec($ch);  
	    //var_dump(curl_error($ch));  //查看报错信息  
	  
	    curl_close($ch);  
	    return $ret;  


	}
	
	/**
	 * GET 请求
	 * @param  $url	请求地址
	 */
	function get($url,$data=array() ){
		$ch = curl_init();	// 初始化CURL句柄
		if(!empty($data) ){
			$postfix=http_build_query($data);
			$url=$url."?".$postfix;
		}
		curl_setopt($ch, CURLOPT_URL, $url);	//设置请求的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);		// 设为TRUE把curl_exec()结果转化为字串，而不是直接输出
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);	//设置超时时间15秒
		$response = curl_exec($ch);	//执行预定义的CURL
		curl_close($ch);	//关闭CURL
		return $response;
	}
}
 ?>