<?php
class CommonFunc{
	public function init() {}
}
function isPNum($num){
	if(preg_match("/^[1-9]\d*$/", $num)  ){
		return true;
	}
	return false;
}

function setKeyValRedis($key,$val,$timeout=60,$server='123.57.251.77'){
	$redis=new Redis;
	$redis->connect($server);
	$redis->SET($key,$val);
	$res=$redis->EXPIRE($key, $timeout);
	return $res;
}

function getValByKeyRedis($key,$server='123.57.251.77'){
	$redis=new Redis;
	$redis->connect($server);
	$res=$redis->GET($key);
	return $res;
}

function delByKeyRedis($key,$server='123.57.251.77'){
	$redis=new Redis;
	$redis->connect($server);
	$res=$redis->del($key);
	return $res;
}

function haveSpace($str){
	if(preg_match('/^[\S]{1,}$/', $str)){
		return false;
	}
	return true;
}

function returnErrorInJsonDie($errno=-1,$errobj='unknown',$errdesc='unknown',$usermsg='数据格式错误或未知错误'){
	
	$res=array('errno'=>$errno,'errmsg'=>'Error: '.$errobj.' '.$errdesc.'.','usermsg'=>$usermsg.'！');
	// var_dump($res);
	exit(json_encode($res));
}

function returnErrorInJson($errno=-1,$errobj='unknown',$errdesc='unknown',$usermsg='数据格式错误或未知错误'){
	
	$res=array('errno'=>$errno,'errmsg'=>'Error:'.$errobj.' '.$errdesc.'.','usermsg'=>$usermsg.'。');
	// var_dump($res);
	return json_encode($res);
}

function returnModelErrorInJsonDie($model){
	$errors=$model->getErrors();
	$objectName=strtolower(get_class($model));
	$key=key($errors);
	returnErrorInJsonDie(-3,$objectName,$key.' '.$errors[$key][0],'操作'.$objectName.'失败');
}

function okInJson($data=''){
	$res=array('errno'=>0,'data'=>$data);
	exit(json_encode($res));
}

function returnModelErrorInJson($model){
	$errors=$account->getErrors();
	$objectName=strtolower(get_class($model));
	$key=key($errors);
	returnErrorInJson(-3,$objectName,$key.' '.$errors[$key][0],'操作'.$objectName.'失败');
}

function returnOkInJsonDie($data=null,$attributes=true){
	// $data=jsonizenc($data);
	$array = jsonize($data,$attributes);
	// var_dump($array);
	$res=array('errno'=>0,'data'=>$array);
	// var_dump($res);
	exit(json_encode($res));
}

function returnOkInJson($data=null,$attributes=true){
	// $data=jsonizenc($data);
	$array = jsonize($data,$attributes);
	// var_dump($array);
	$res=array('errno'=>0,'data'=>$array);
	// var_dump($res);
	return json_encode($res);
}

//给model赋值
function assignToModel($inputs,$model){
	foreach ($inputs as $key => $value) {
		// var_dump($key);
		if(array_key_exists($key,$model->getAttributes())){
			if(!$inputs[$key]){
				continue;
			}
			$model->{$key}=$inputs[$key];
		}
	}
	return $model;
}

//给对象赋值
function assignToObject($inputs,$object){
	foreach ($inputs as $key => $value) {
		// var_dump($key);
		if(property_exists($object,$key) ){
			if(!$inputs[$key]){
				continue;
			}
			$object->{$key}=$inputs[$key];
		}
	}
	return $object;
}

//在多维数组中
function deepInArray($value, $array, $case_insensitive = true){
    foreach($array as $item){
        if(is_array($item)) $ret = deepInArray($value, $item, $case_insensitive);
        else $ret = ($case_insensitive) ? strtolower($item)==$value : $item==$value;
        // var_dump($ret);
        if($ret)return $ret;
    }
    return false;
}

function isControlActionInArray($array){
	if(empty($_GET)||empty($_GET['r'])){
		returnErrorInJsonDie(-2,'GET r','null','页面参数输入或格式错误');
	}
	$arr=explode('/', $_GET['r']);
	if(count($arr)!=2){
		returnErrorInJsonDie(-2,'GET r','null','页面参数输入或格式错误');
	}
	foreach ($array as $key => $value) {
		if(strtolower($arr[0])==strtolower($key) ){
			foreach ($value as $key1 => $value1) {
				if(strtolower($arr[1])==strtolower($value1) ){
					return true;
				}
			}
		}
	}
	return false;
}


//检查手机格式
function checkPhoneNumFormat($phoneNum){
	if(preg_match("/^[1-9]{1}[0-9]{10}$/", $phoneNum)  ){
		return true;
	}
	return false;
}

/**
 * calls json_encode(jsonize())
 * @see jsonize
 */
function jsonizenc($data, $attributes=true, $onlySpecifiedRelations = false, $onlySpecifiedAttributes = false) {
	return json_encode(jsonize($data, $attributes, $onlySpecifiedRelations, $onlySpecifiedAttributes));
}
/**
 * 
 * @param mixed $data an ActiveRecord or array of ActiveRecord
 * @param $attributes array of relations to be loaded e.g array('client','items'=>array('product')) // nested relations
 * @param bool $onlySpecifiedRelations if to send all loaded relations or only the ones specified in $attributes 
 * @param bool $onlySpecifiedAttributes if to send all attributes or only the ones specified in $attributes 
 */
function jsonize($data, $attributes=true, $onlySpecifiedRelations = false, $onlySpecifiedAttributes = false) {
	if (is_array($data)) { // can be an empty array
		$json=array();
		foreach($data as $key => $ar) {
			$json[$key]=jsonize($ar,$attributes, $onlySpecifiedRelations, $onlySpecifiedAttributes);
		}
		return $json;
	}
	if ($data===null) return null;
	if ($data===0) return 0;
	if ($data==='') return '';
	if ($data===false) return false;
	if ($data==null) return null;
	if (!is_object($data)) return $data; //json_encode($data);
	$ar=$data;
	//Yii::log('jsonize class '.get_class($ar));
	$relations=$ar->relations();
	if ($onlySpecifiedAttributes) {
		$json=array();
	} else {
		$json=$ar->getAttributes(); // on va dire sans ca ?
		foreach($json as $name=>$value) {
			if (is_object($value) || is_array($value)) { // relation defined by virtual attribute 
				if ($onlySpecifiedRelations) 
					unset($json[$name]); // will be retrieved later 
				else { // can lead to stack overflow if it loads recursively more objects, therefore, only load explicitly specified relations
					$json[$name]=jsonize($value, @$attributes[$name]?:true, true, $onlySpecifiedAttributes);
				}
			}
		}
		if (!$onlySpecifiedRelations) { // include eagerly loaded relations
			foreach($relations as $relName=>$relDef) {
				if ($ar->hasRelated($relName)) {
					$rel = $ar->getRelated($relName);
					$spec = true; // (!is_array($rel) && $rel && $rel->isNewRecord)?true:$onlySpecifiedRelations;
					$json[$relName]=jsonize($rel, @$attributes[$relName]?:true, $spec, $onlySpecifiedAttributes);
				}
			}
		}
	}
	// include explicitly requested relations or virtual columns or attributes 
	if (is_array($attributes)) {
		foreach($attributes as $name=>$value) {
			if (is_numeric($name)) {// simple relation, handle scalar values, activerecord objects and arrays of ActiveRecord
				$related = $ar->$value;
				$json[$value]=(is_object($related) || is_array($related))?jsonize($related, true, true, $onlySpecifiedAttributes):$related;
			} else // nested rels
				$json[$name]=jsonize($ar->$name, $value, true, $onlySpecifiedAttributes);
		}
	}
	return $json;
}

?>