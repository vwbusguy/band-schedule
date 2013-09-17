<?php
session_start();
require_once('../services/chkSession.php');
require_once('../includes/head.php');
require_once('../includes/globalnav.php');
?>
<script type="text/javascript">
$(document).ready(function(){
$('#frmChgPasswd').submit(function(e){
	e.preventDefault();
	password = $('#password').val();
	if (password.length < 6){
		$('#password-error').text('Please enter a password of at least six characters in length.');
		return 'False';
	}
	md5 = $.md5(password);
	$.ajax({
		type: "POST",
		url: '/services/chgPass.php',
		data: {'md5' : md5},
		dataType: 'json',
		success: function(data){
			if (data.status == 'ok'){
				alert('Password has been successfully changed!');
			}
			else{
				$('#password-error').text(data.message);
			}
		}

	});	

});

})

</script>

<div class="container">
<h1>Change Password</h1>
<br/>
<form id="frmChgPasswd" method="post" class="form">
<input type="password" name ="password" id="password"><br/>
<p class="red" id="password-error"></p>
<input class="btn btn-primary btn-large" type="submit" value="Update Password" name="submit" id="subPass">
</form>

</div>
<?php require_once('../includes/footer.php'); ?>
