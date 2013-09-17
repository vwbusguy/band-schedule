<?php
session_start();
if (!isset($_COOKIE['auth'])){
	setcookie('fccw','auth',time()+60*60*24*30,'/','worship.foothill.org',False,False);
}

require_once('includes/head.php');
?>
<div class="gray login-container">

<h1 id="login-title">FCC Worship Scheduling Center</h1>

<?php if (isset($_GET['logout'])){
	echo "<p id=\"logged-out\">User " . $_GET['logout'] . " has been logged out.</p>";
}
?>
<form id="login">
<h2 id="login-heading">Login</h2>
<input id="username" type="text" name="username" placeholder="Username"><br/>
<input id="password" type="password" name="password" placeholder="Password"><br/>
<button class="btn btn-large btn-primary" type="submit">Sign In</button>
</form>
</div>
<?php require_once('includes/footer.php');?>
