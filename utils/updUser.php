<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/head.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/globalnav.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
?>
<div class="container">
<h1>Manage Users</h1>
<select id="user-select"></select>
<form target="./updUser.php" id="frmUpdProfile" class="form" method="post">
<?php
$user = new users();
$userinfo = $user->getUserData();
if ($userinfo['user_level'] != 1){
	header('Location: /');
}
if (isset($_POST['submit'])){
	unset($_POST['submit']);
	$update = $user->updUserInfo($_POST);
	if ($update['status'] == "ok"){
		echo "<p>Info for username " . $_POST['username'] . " has been successfully updated.</p>";
	}else{
		echo "<p>" . $update['error'] . "</p>";
	}
}
if ($_POST['username'] == $_SESSION['username']){
	$userinfo = $user->getUserData();
}
$levels = $user->getUserLevels();
echo "<input type='hidden' id='inpUserName' name='username' value=" . $_SESSION['username']. ">";
foreach ($userinfo as $key=>$val){
if ($key != 'id'){
	if ($key == 'last_login' or $key == 'username'){
                $fieldTitle = ucfirst(str_replace('_',' ',$key));
                echo "<h4 id='txt$key'>$fieldTitle: $val</h4>";
	}elseif($key != 'user_level' and $key != 'status'){
		$fieldTitle = ucfirst(str_replace('_',' ',$key));
		echo "<h4>$fieldTitle</h4>";
		echo "<input type=\"text\" value=\"$val\" placeholder=\"$val\" name=\"$key\" id=\"$key\"><br/>";  
	}elseif($key == "user_level"){
		echo '<h4 id="txtusr_level">User Level</h4>';
		echo '<select name="user_level" id="user_level">';
		foreach ($levels as $level){
			if (!isset($level['status'])){
				echo '<option value="' . $level['user_level'] . '"' ;
				if ($level['user_level'] == $val){
					echo ' selected="selected"';
				}
				echo '>' . $level['level_name'] . '</option>';
			}
		} 
		echo '</select>';
	}elseif($key == "status"){
		echo "<h4>Status </h4>";
		echo "<select name=\"status\" id=\"status\">";
		$statuses = $user->getUserStatuses();
		foreach ($statuses as $stat){
			$option = "<option value=\"" . $stat['id'] . "\"";
			if ($stat['id'] == $val){
				$option .= " selected=\"selected\"";
			}
			$option .=">" . $stat['status'] . "</option>";
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
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){

$.get('/services/getUserList.php',function(data) {
	data = JSON.parse(data);
	list = '<option value="Select User" selected="selected" disabled>Select User</option>';
	$.each(data,function(i,u){
		if (i != 'status'){
			list += '<option value="' + u.username +'">' + u.username + '</option>'; 
		}
	});
	$('#user-select').html(list);
});

function setUserLevel(user_level){
	$('#user_level').children().each(function(){
		if ($(this).val() == user_level){
			$(this).attr('selected','selected');
		}else{
			$(this).removeAttr('selected');
		}
	});
}

$('#user-select').change(function(){
	username = $(this).val();
	$.get('/services/getUserInfo.php',{username: username}).done(function(data){
		u = JSON.parse(data);
		$('#inpUserName').val(u.username);
		$('#txtusername').text('Username: ' + u.username);
		$('#txtlast_login').text('Last Login: ' + u.last_login);
		$('#first_name').val(u.first_name).attr('placeholder',u.first_name);
		$('#last_name').val(u.last_name).attr('placeholder',u.last_name);
		$('#phone').val(u.phone).attr('placeholder',u.phone);
		$('#phone2').val(u.phone2).attr('placeholder',u.phone2);
		$('#email').val(u.email).attr('placeholder',u.email);
		setUserLevel(u.user_level);
		$('#status').children().each(function(){
			if ($(this).val() == u.status){
				$(this).attr('selected','selected');
			}else{
				$(this).removeAttr('selected');
			}
		});
		
	});

})


})


</script>
