<?php
session_start();
require_once('chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');

class delEvent{

public function __construct(){
	if (!isset($_SESSION['username'])){
		$this->retError('User is not logged in.');
	}
	if ($_SESSION['user_level'] > 1){
		$this->retError('Insuffient permissions to change user.');
	}
	$this->deacEvent($_POST['eventid']);
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function deacEvent($eventid){
	$event = new events;
	$event->deactivateEvent($eventid);
	if (isset($event->error)){
		$this->retError($event->error);
	}
	return $result;
}

}
$foo = new delEvent;
?>
