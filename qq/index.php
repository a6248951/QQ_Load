<?php
require '../../../common.inc.php';
require 'init.inc.php';
var_dump("aaaaaaaa");
$success = 0;
$DS = array();

//var_dump($DS);
//var_dump($_SESSION['qq_access_token']);
//exit;

if($_SESSION['qq_access_token']) {
	
	$par = 'access_token='.$_SESSION['qq_access_token'];
	$rec = dcurl(QQ_ME_URL, $par);
	if(strpos($rec, 'client_id') !== false) {
		$rec = str_replace('callback(', '', $rec);
		$rec = str_replace(');', '', $rec);
		$rec = trim($rec);
		$arr = json_decode($rec, true);
		$openid = $arr['openid'];		
		if($OAUTH[$site]['sync']) set_cookie('qq_openid', encrypt($openid, AJ_KEY.'QQID'), $AJ_TIME + $_SESSION['qq_access_time']);
		$par = 'access_token='.$_SESSION['qq_access_token'].'&oauth_consumer_key='.QQ_ID.'&openid='.$openid;
		$rec = dcurl(QQ_USERINFO_URL, $par);
		if(strpos($rec, 'nickname') !== false) {
			$success = 1;
			$arr = json_decode($rec, true);
			$nickname = convert($arr['nickname'], 'UTF-8', AJ_CHARSET);
			$avatar = $arr['figureurl_2'];
			$url = '';
			$DS = array('qq_access_token', 'qq_access_time', 'state');
		}
	}
}
require '../aijiacms.inc.php';
?>
