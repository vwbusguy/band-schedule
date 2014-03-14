    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">FCC Worship Center</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="/">Home</a></li>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/users.class.php');
$user = new users();
$level = $user->getUserRoleId($_SESSION['username']);
if ($level > 2){
	echo " <li><a href=\"/events.php\">Events</a></li>";
}else{
	echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=#>Events<b class=\"caret\"></b></a>";
        echo "<ul class=\"dropdown-menu\">";
        echo "<li><a href=\"/utils/addEvent.php\">Add Event</a></li>";
        echo "<li><a href=\"/events.php\">Manage Events</a></li>";
        echo "</ul></li>";
}
	echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=#>Account Management<b class=\"caret\"></b></a>";
	echo "<ul class=\"dropdown-menu\">";
	echo "<li><a href=\"/updateProfile.php\">My Profile</a></li>";
	echo "<li><a href=\"/utils/chgPasswd.php\">Change Password</a></li>";
if ($level <= 2){
	echo "<li><a href=\"/utils/adduser.php\">Add Users</a></li>";
}
if ($level == 1){
	echo "<li><a href=\"/mail.php?type=all\">Email All Users</a></li>";
	echo "<li><a href=\"/utils/updUser.php\">Manage Users</a></li>";
}
	echo "</ul></li>";
?>
              <li><a href="/logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
