<?php
// header('Content-Type: text/html; charset=utf-8');
header("Content-Type: application/json", true);
require("HttpRequest.php");
require("CommonFunc.php");
$hr=new HttpRequest;
if(empty($_GET['oauth_token'])){
	returnErrorInJsonDie();
}
if(empty($_GET['board'])){
	returnErrorInJsonDie();
}
if(empty($_GET['threadId'])|| !isPNum($_GET['threadId']) ){
	returnErrorInJsonDie();
}

$res=$hr->get("http://bbs.byr.cn/open/threads/".$_GET['board']."/".$_GET['threadId'].".json",$_GET);
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