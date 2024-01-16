<?PHP
session_start();

include("database.php");
if( !verifyUser($con) ) 
{
	header( "Location: index.php" );
	return false;
}
?>
<?PHP
$id_user	= $_SESSION["id_user"];
$email		= $_SESSION["email"];
$username	= $_SESSION["username"];

// Fetch notifications for the current user
$SQL_fetch_notifications = "SELECT * FROM `notifications` WHERE `user_id`='$id_user' ORDER BY `created_at` DESC";
$result_notifications = mysqli_query($con, $SQL_fetch_notifications);

?>
<?PHP
$act 	= (isset($_REQUEST['act'])) ? trim($_REQUEST['act']) : '';	

$id_task= (isset($_GET['id_task'])) ? trim($_GET['id_task']) : '0';

$task	= (isset($_POST['task'])) ? trim($_POST['task']) : '0';
$task	=	mysqli_real_escape_string($con, $task);

$success = "";


$SQL_list = "SELECT * FROM `user` WHERE `id_user`='$id_user' ";
$result = mysqli_query($con, $SQL_list) ;
$data	= mysqli_fetch_array($result);
$name 	= $data["name"];
$photo		= $data["photo"];
if(!$photo) $photo = "noimage.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>ProCollab</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Poppins",  sans-serif}
a:link { text-decoration: none; }
.w3-bar-block .w3-bar-item {padding: 16px}
.w3-biru {background-color:#f6f9ff;}

/* Full height image header */
.bgimg-1 {
  background-position: top;
  background-size: cover;
  background-attachment:fixed;
  background-image: url(images/bg_noti.jpg);
  min-height:100%;
  background-color: rgba(0, 0, 0, 0.3);
  background-blend-mode: overlay;
}
</style>
</head>
<body class=" bgimg-1">

<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-blue w3-card" style="z-index:3;width:250px;" id="mySidebar">
	<a href="main.php" class="w3-bar-item w3-large"><img src="images/logo2.png" class="w3-padding" style="width:150px;"></a>
	<a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
	class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
	
	<div class="w3-padding"></div>
	
	<a href="main.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="fa fa-fw fa-tachometer-alt w3-margin-right"></i> Dashboard</a>

	<a href="tasks.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="far fa-fw fa-check-circle w3-margin-right"></i> Tasks</a>	
	
	<a href="notification.php" class="w3-bar-item w3-button w3-round-xxlarge w3-white w3-text-blue">
	<i class="far fa-fw fa-bell w3-margin-right"></i> Notification</a>
	
	<a href="timetable.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="far fa-fw fa-calendar-alt w3-margin-right"></i> Timetable</a>	
	
	<span class="w3-bottom" style="z-index:3;width:250px;">
	<a href="logout.php" class="w3-bar-item w3-button  w3-round-xxlarge">
	<i class="fa fa-fw fa-sign-out-alt w3-margin-right"></i> Logout</a>
	<div class="w3-padding"></div>
	</span>
</nav>


<!-- Overlay effect when opening the side navigation on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="Close Sidemenu" id="myOverlay"></div>

<!-- Page content -->
<div class="w3-main" style="margin-left:250px;">


<div class="w3-bar ">

	<i class="fa fa-bars w3-buttonx w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>

	<div class="w3-large w3-buttonx w3-bar-item w3-right w3-dropdown-hover">
      <button class="w3-button"><img src="upload/<?PHP echo $photo;?>" class="w3-circle" style="width:30px"> <?PHP echo $_SESSION["username"];?> <i class="fa fa-fw fa-chevron-down w3-small"></i></button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="profile.php" class="w3-bar-item w3-button"><i class="fa fa-fw fa-user-cog "></i> Profile</a>
        <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-fw fa-sign-out-alt "></i> Signout</a>
      </div>
    </div>

</div>



<div class="w3-container w3-content" style="max-width:1000px;"> 
	<div class="w3-padding"></div>
	<div class="w3-xlarge"><i class="fas fa-fw fa-bell w3-text-red"></i> <b>Notifications</b></div>
</div>

	
<div class="w3-container ">

    <!-- Page Container -->
    <div class="w3-containerx w3-content w3-padding-16 " style="max-width:1000px;">
        <!-- The Grid -->
        <div class="w3-row w3-padding">

            <div class="w3-round-xxlarge w3-card w3-dark-gray w3-opacityx w3-padding w3-padding-16">
                <div class="w3-padding">
                    <?php
                    // Fetch and display notifications
                    while ($notification = mysqli_fetch_array($result_notifications)) {
                        echo "<div class='w3-row w3-padding w3-border-bottom'>";
                        echo "<div class='w3-col m1'><i class='fa fa-user-circle fa-2x'></i></div>";
                        echo "<div class='w3-col m10'>{$notification['message']}</div>";
                        // echo "<div class='w3-col m1 w3-center'><a href='#'><i class='fa fa-trash w3-hover-text-red'></i></a></div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- End Grid -->
        </div>

        <!-- End Page Container -->
    </div>

</div>
<!-- container end -->
	

	

<div class="w3-padding-24"></div>

     
</div>


<script>
var openInbox = document.getElementById("myBtn");
openInbox.click();

function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
}

function myFunc(id) {
  var x = document.getElementById(id);
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show"; 
    x.previousElementSibling.className += " w3-pale-red";
  } else { 
    x.className = x.className.replace(" w3-show", "");
    x.previousElementSibling.className = 
    x.previousElementSibling.className.replace(" w3-red", "");
  }
}

</script>

</body>
</html> 
