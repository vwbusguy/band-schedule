<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');
session_start();
require_once('./chkSession.php');

class resEvent{

function __construct(){
	if (!isset($_POST['username'])){
		$username = $_SESSION['username'];
	}else{
		$username = $_POST['username'];
	}
	if (!isset($_POST['type'])){
		$this->retError('No type parameter specified.');
	}else{
		$type = $_POST['type'];
	}
	if (!isset($_POST['eventid'])){
		$this->retError('No eventid specified.');
	}else{
		$eventid = $_POST['eventid'];
	}
	$uid = $this->getUserId($username);
	if ($type == 'confirm'){
		$result = $this->usrConfirm($uid,$eventid);
	}else{
		$this->retError('Invalid type.');
	}
	$result['username'] = $username;
	echo json_encode($result);
	return True;
	
}

private function retError($message){
	$result['status'] = "failed";
	$result['message'] = "$message";
	echo json_encode($result);
	die();
}

private function getUserId($username){
	$user = new users;
	$usrId = $user->getUserId($username);
	if (isset($user->error)){
		$this->retError($user->error);
	}
	return $usrId;
}

private function usrConfirm($uid,$eventid){
	$event = new events;
	$event->usrConfirm($uid,$eventid);
	if (isset($event->error)){
		$this->retError($event->error);
	}
	$result['status'] = 'success';
	$result['uid'] = $uid;
	$result['eventid'] = $eventid;
	return $result;
}

}

$foo = new resEvent;

?>
