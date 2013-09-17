<?php

//Returns Array of user levels

require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
require_once('./chkSession.php');

class userLevels{

public function __construct(){
	$this->user = new users();
	$data = $this->getLevels();
	echo json_encode($data,JSON_FORCE_OBJECT);
}

private function getLevels(){
	$result = $this->user->getUserLevels();
	if ($db->error == 1){
		$result['status'] = 'failed';
		$result['error'] = $user->errormsg;
	}else{
		$result['status'] = 'ok';
	}
	return $result;	
}

}

$foo = new userLevels();
	
?>
