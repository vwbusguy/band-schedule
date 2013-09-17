<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/db.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/chkSession.php'); 
if (isset($_POST['username'])){
	$error = 0;
	$data = array();
	foreach ($_POST as $key=>$val){
		if ($key != 'Submit'){
			if ($key == 'password'){
				$data[$key] = md5($val);
			}elseif ($key == 'username'){
				$data[$key] = strtolower($val);
			}else{
				$data[$key] = $val;
			}
		}
	}
	$data['status'] = 1;
	$db = new db();
	if ($db->error == 1){
		echo("<script type=\"text/javascript\">alert(\"" . $db->errormsg . "\")</script>");
		$error = 1;
	}else{
		$db->insert('users',$data);
	}
        if ($db->error == 1){
		$error = 1;
                echo("<script type=\"text/javascript\">alert(\"" . $db->errormsg . "\")</script>");
        }else{

		$db->connection->commit();
		$db->close();
	}
	if ($error == 0){
		echo "<script type=\"text/javascript\">alert('User " . $data['username'] . " was successfully added!')</script>";
	}
}

require_once('../includes/head.php');
require_once('../includes/globalnav.php');
?>
<div class="container">
<h1> Add User </h1>

<form id="frmAddUser" action="./adduser.php" method="post">
<input type="text" name="username" placeholder="Username"><br/>
<input type="password" name="password" placeholder="Password"><br/>
Level: <br/><div id="ulevel"><img src="/images/sm-loader.gif"></div><br/>
<input type="text" name="first_name" placeholder="First Name"><br/>
<input type="text" name="last_name" placeholder="Last Name"><br/>
<input type="text" name="phone" placeholder="Phone #"><br/>
<input type="text" name="phone2" placeholder="Second Phone #"><br/>
<input type="text" name="email" placeholder="Email Address"><br/>
<button class="btn btn-large btn-primary" type="submit">Add User</button>
</form>
</div>
<?php require_once('../includes/footer.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
        getUserLevel(dispUserLevels);

	function dispUserLevels(data){
        	if ((data.status != 'ok') || (data.level > 2)){
                	alert("You don't have sufficient permissions to add users or are not logged in.");
                	window.location.replace('/index.php');
        	}else{
                	window.ulevel = data.level;
        	}
        }

$.get('../services/getUserLevels.php',function(data){
        ulInput = '<select name="user_level">';
        $.each(data,function(i){
        if (i != 'status'){
                user_level = $(this).attr("user_level");
                if (user_level >= ulevel){
                        ulInput += '<option value="' + $(this).attr("user_level") + '">' + $(this).attr("level_name") + '</option>';
                }
        }
        });
        ulInput += '</select>';
        $('#ulevel').html(ulInput);

        },"json");
});


</script>

