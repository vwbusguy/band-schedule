<?php
session_start();
require_once('chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/events.class.php');

class chgDate{

public function __construct($type='id'){
	if (!isset($_SESSION['username'])){
		$this->retError('User is not logged in.');
	}
	if ($_SESSION['user_level'] > 2){
		$this->retError('Insuffient permissions to change the practice date.');
	}
	$this->chgDate($_POST['eventid'],$_POST['date']);
	$result['status'] = 'ok';
	echo json_encode($result);
}

private function retError($message){
	$result['status'] = 'failed';
	$result['message'] = $message;
	die(json_encode($result));
}

private function chgDate($eventid, $date){
	$event = new events;
	$event->setPractice($eventid,$date);
	if (isset($event->error)){
		$this->retError($event->error);
	}
	return $result;
}

}
$foo = new chgDate;
?>
