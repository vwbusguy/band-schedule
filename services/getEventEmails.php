<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');
class getEventEmails{

public function __construct(){
	$this->event = new events;
	if (!isset($_GET['eventid'])){
		$this->retError('No event id specified.');
	}
	$result['data'] = $this->getEventEmails($_GET['eventid']);
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function getEventEmails($eventid){
	$result = $this->event->getEventEmails($eventid);
	if (isset($this->event->error)){
		$this->retError($this->user->error);
	}
	return $result;
}

}
$foo = new getEventEmails;
?>
