<?php

require_once('/srv/www/worship/classes/db.class.php');

class users{

public function __construct(){
	$this->db = new db();	
}

public function getUserId($username){
	$sql = "SELECT id from users where username = '$username'";
	$result = $this->db->select($sql);
        if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
	return $result[id];	
}

public function getRoleId($role){
	$sql = "SELECT from user_level where level_name = '$role'";
        $roles = $this->db->select($sql);
        if (!isset($roles['user_level'])){
                $this->error = "Invalid user role name";
                return False;
        }
	return $roles['user_level'];
}

public function getUserNiceName($userid){
	$sql = "SELECT first_name,last_name from users where id = $userid";
	$result = $this->db->select($sql);
        if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
        return $result;
}

public function getUserData($username){
	if (!$username){
		$username = $_SESSION['username'];
	}
	$sql = "SELECT * from users where username = '$username'";
	$result = $this->db->select($sql);
	unset($result['password']);
	return $result;
}

public function getUserList(){
	$sql = "select username from users";
	$result = $this->db->selectAll($sql);
	return $result;
}

public function getLeaderList(){
        $sql = "select id,username from users where user_level <= 2 and status != 2";
        $result = $this->db->selectAll($sql);
        return $result;
}


public function chgUserPass($user,$md5){
	$this->db->update('users','password',"'$md5'","username = '$user'");
	if ($this->db->error == 1){
		$this->error = $this->db->errormsg;
		return FALSE;
	}
	return true;
}

public function chgUserRole($user,$role){
	$roleNum = $this->getRoleId($role);
	if ($roleNum === False){
		return FALSE;
	}
	$this->db->update('users','user_level',"'$roleNum'","username = '$user'");
	if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
        return true;
}

public function getRoleLevel($username){
	$sql = "SELECT level_name from user_level where user_level = (SELECT user_level from users where username = '$username')";
	$result = $this->db->select($sql);
        if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
	return $result['level_name'];
}

public function getUserRoleId($username){
	$sql = "SELECT user_level from users where username = '$username'";
        $result = $this->db->select($sql);
        if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
        return $result['user_level'];
}

public function hasPerms($roleid,$username){
	if (!$userRole = $this->getUserRoleId($username)){
		return FALSE;
	}
	if ($userRole <= $roleid){
		return True;
	}
	return False;
}

public function chkLogin($username,$md5){
	$sql = "SELECT username,password from users where username = '$username'";
        $result = $this->db->select($sql);
        if ($this->db->error == 1){
                $this->error = $db->errormsg;
		return FALSE;
        }
        if ($result['password'] == $md5){
		return True;
	}
	return False;
}

public function setLastLogin($username){
	$this->db->update('users','last_login','CURRENT_TIMESTAMP()',"username = '$username'");
	        if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
        return true;
}

public function getAllEmails(){
	$sql = "SELECT email FROM users WHERE status != 2";
	$result = $this->db->selectAll($sql);
        if ($this->db->error == 1){
                $result['status'] = 'failed';
                $result['error'] = $db->errormsg;
        }else{
                $result['status'] = 'ok';
        }
        return $result;
}



public function getNiceUserLevel($roleId){
	$sql = "SELECT level_name from user_level where user_level = '$roleId'";
	$result = $this->db->select($sql);
	if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
        return $result['level_name'];
}

public function getUserLevels(){
        $db = new db();
        $sql = 'SELECT * from user_level';
        $result = $db->selectAll($sql);
        if ($db->error == 1){
                $result['status'] = 'failed';
                $result['error'] = $db->errormsg;
        }else{
                $result['status'] = 'ok';
        }
        return $result;
}

public function getNiceStatus($status){
        $sql = "SELECT status from user_status where id = '$status'";
        $result = $this->db->select($sql);
        if ($this->db->error == 1){
                $this->error = $this->db->errormsg;
                return FALSE;
        }
        return $result['status'];
}

public function getUserStatuses(){
        $db = new db();
        $sql = 'SELECT * from user_status';
        $result = $db->selectAll($sql);
        if ($db->error == 1){
                $result['status'] = 'failed';
                $result['error'] = $db->errormsg;
	}else{
        	return $result;
	}
}

public function updUserInfo($data){
	$db = new db();
	foreach ($data as $key => $val){
		if ($key != 'username'){
			$db->update('users',$key, "'$val'","`username` = '" . $data['username'] ."'");
		}
	}
        if ($db->error == 1){
                $result['error'] = $db->errormsg;
                $result['status'] = 'failed';
		return $result;
        }
	$result['status'] = 'ok';
        return $result;

}

}
?>
