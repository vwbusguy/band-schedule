<?php
session_start();
require_once('./services/chkSession.php');
require_once('./includes/head.php');
require_once('./includes/globalnav.php');
require_once('./classes/users.class.php');
?>
<div class="container">
<h1>Update Profile</h1>
<p><a href="/utils/chgPasswd.php">Change Your Password</a></p>
<form target="./updateProfile.php" id="frmNewProfile" class="form" method="post">
<?php
$user = new users();
if (isset($_POST['submit'])){
        unset($_POST['submit']);
	$_POST['username'] = $_SESSION['username'];
	
        $update = $user->updUserInfo($_POST);
        if ($update['status'] == "ok"){
                echo "<p>Info for username " . $_POST['username'] . " has been successfully updated.</p>";
        }else{
                echo "<p>" . $update['error'] . "</p>";
        }
}
$userinfo = $user->getUserData();
foreach ($userinfo as $key=>$val){
if ($key != 'id'){
	if ($key == 'last_login' or $key == 'username'){
                $fieldTitle = ucfirst(str_replace('_',' ',$key));
                echo "<h4>$fieldTitle: $val</h4>";
	}elseif($key != 'user_level' and $key != 'status'){
		$fieldTitle = ucfirst(str_replace('_',' ',$key));
		echo "<h4>$fieldTitle</h4>";
		echo "<input type=\"text\" value=\"$val\" placeholder=\"$val\" name=\"$key\" id=\"$key\"><br/>";  
	}elseif($key == "user_level"){
		$level = $user->getNiceUserLevel($val);
		echo "<h4>User Role: $level </h4>";
	}elseif($key == "status"){
		echo "<h4>Status </h4>";
		echo "<select id=\"status\" name=\"status\">";
		$statuses = $user->getUserStatuses();
		foreach ($statuses as $stat){
			$option = "<option value=\"" . $stat['id'] . "\"";
			if ($stat['id'] == $val){
				$option .= " selected=\"selected\"";
			}
			$option .=">" . ucfirst($stat['status']) . "</option>";
			echo $option;
		}
		echo "</select>";
	}
}

}

?>
<input class="btn btn-large btn-primary" type="submit" value="Update" name="submit"></input>
</form>
</div>
<?php require_once('./includes/footer.php'); ?>
