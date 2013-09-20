<?php
session_start();
require_once('chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');

class chgEventStatus{

public function __construct(){
	if (!isset($_SESSION['username'])){
		$this->retError('User is not logged in.');
	}
	if ($_SESSION['user_level'] > 2){
		$this->retError('Insuffient permissions to change user.');
	}
	$this->chgEventStatus($_POST['eventid'],$_POST['status']);
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function chgEventStatus($eventid, $status){
	$event = new events;
	$event->chgEventStatus($eventid,$status);
	if (isset($event->error)){
		$this->retError($event->error);
	}
	return $result;
}

}
$foo = new chgEventStatus;
?>
