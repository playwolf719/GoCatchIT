<?php
// header('Content-Type: text/html; charset=utf-8');
header("Content-Type: application/json", true);
require("HttpRequest.php");
require("CommonFunc.php");
$hr=new HttpRequest;
if(empty($_GET['oauth_token'])){
	returnErrorInJsonDie();
}
$res=$hr->get("http://bbs.byr.cn/open/user/getinfo.json",$_GET);
// $res=$hr->get("http://bbs.byr.cn/open/search/article.json",$_GET);
$resArr=json_decode($res,true);
// var_dump($resArr);
// die();
if(!empty($resArr['code']) ){
	returnErrorInJsonDie($resArr['code'],$resArr['request'],'byr',$resArr['msg']);
}else{
	returnOkInJsonDie($resArr);
}
?>