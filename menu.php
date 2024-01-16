<!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar w3-text-white" id="myNavbar">

    <!-- Right-sided navbar links -->
    <div class="w3-right w3-hide-small"> 
	  
		<?PHP if(isset($_SESSION["username"])) {?>
		<a href="profile.php" class="w3-bar-item1 w3-button">Profile</a>
		<?PHP } ?>

		<?PHP if(isset($_SESSION["username"])) {?>
		<a href="logout.php" class="w3-bar-item1 w3-button"><i class="fa fa-fw fa-lg fa-power-off"></i>   Logout</a>
		<?PHP } else { ?>
		<a href="login.php" class="w3-bar-item1 w3-button"><i class="fa fa-fw fa-lg fa-lock"></i>   <b>Log in</b> </a>
		<a href="register.php" class="w3-bar-item1 w3-button"><b>Register</b></a>
		<?PHP } ?>
	  
    </div>
    <!-- Hide right-floated links on small screens and replace them with a menu icon -->


	<a href="javascript:void(0)" class="w3-bar-item w3-text-black w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
      <i class="fa fa-bars"></i>
    </a>
	

  </div>
</div>

<!-- Sidebar on small screens when clicking the menu icon -->
<nav class="w3-sidebar w3-bar-block w3-gray w3-card w3-animate-left w3-hide-medium w3-hide-large" style="display:none" id="mySidebar">
	<a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-large w3-padding-16">Close ×</a>
	
	<?PHP if(isset($_SESSION["username"])) {?>
	<a href="profile.php" onclick="w3_close()" class="w3-bar-item w3-button">Profile</a>
	<?PHP } ?>
	
	<?PHP if(isset($_SESSION["username"])) {?>
	<a href="logout.php" onclick="w3_close()" class="w3-bar-item w3-button">Logout</a>
	<?PHP } else { ?>
	<a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Log In</a>
	<?PHP } ?>

</nav>