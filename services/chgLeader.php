<?php
session_start();
require_once('chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');

class chgLeader{

public function __construct($type='id'){
	if (!isset($_SESSION['username'])){
		$this->retError('User is not logged in.');
	}
	if ($_SESSION['user_level'] > 2){
		$this->retError('Insuffient permissions to change user.');
	}
	$this->chgLeader($_POST['eventid'],$_POST['leaderid']);
	$this->cnfEvent($_POST['eventid'],$_POST['leaderid']);
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function chgLeader($eventid, $leaderid){
	$event = new events;
	$event->setLeader($eventid,$leaderid);
	if (isset($event->error)){
		$this->retError($event->error);
	}
	return true;
}

private function cnfEvent($eventid, $leaderid){
	$event = new events;
	$status = $event->chkUserEventStatus($eventid,$leaderid);
	if ($status == Null){
		$event->usrConfirm($leaderid,$eventid);
	}elseif ($status != 1){
		$event->chgUserEvent($leaderid,$eventid,1);
	}
	if (isset($event->error)){
		$this->retError($event->error);
	}
	return true;
}

}
$foo = new chgLeader;
?>
