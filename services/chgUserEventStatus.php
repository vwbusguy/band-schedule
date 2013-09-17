<?php
session_start();
require_once('chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');

class chgUserEventStatus{

public function __construct($type='id'){
	if (!isset($_SESSION['username'])){
		$this->retError('User is not logged in.');
	}
	$this->chgUserEventStatus($_POST['eventid'],$_SESSION['id'],$_POST['stat']);
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function chgUserEventStatus($eventid, $userid,$status){
	$event = new events;
	$event->chgUserEvent($userid,$eventid,$status);
	if (isset($event->error)){
		$this->retError($event->error);
	}
	return;
}

}
$foo = new chgUserEventStatus;
?>
