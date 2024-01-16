<?PHP

ob_start(); // Start output buffering

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
	
$act 		= (isset($_REQUEST['act'])) ? trim($_REQUEST['act']) : '';	

$id_project	= (isset($_GET['id_project'])) ? trim($_GET['id_project']) : '0';

$color		= (isset($_POST['color'])) ? trim($_POST['color']) : '0';
$project	= (isset($_POST['project'])) ? trim($_POST['project']) : '0';
$project	=  mysqli_real_escape_string($con, $project);

$success = "";

if($act == "add")
{	
	$SQL_insert = "INSERT INTO `project`(`id_project`, `id_user`, `project`, `color`) VALUES (NULL, '$id_user', '$project', '$color')";									
$result = mysqli_query($con, $SQL_insert);

if ($result) {
    // Retrieve the last inserted project ID
    $lastProjectId = mysqli_insert_id($con);

    // Handle inviting members to the project
    if (isset($_POST['invited_members']) && !empty($_POST['invited_members'])) {
        $invitedMembers = explode(',', $_POST['invited_members']);
        foreach ($invitedMembers as $invitedMember) {
            $invitedMember = trim($invitedMember); // Trim any spaces
            // Query to check if the invited member exists
            $checkMemberQuery = "SELECT id_user FROM `user` WHERE `username` = '$invitedMember'";
            $memberResult = mysqli_query($con, $checkMemberQuery);
            $memberData = mysqli_fetch_array($memberResult);

            if ($memberData) {
                $invitedMemberId = $memberData['id_user'];

                // Insert the invited member into the project_participants table
                $insertMemberQuery = "INSERT INTO project_participants (project_id, member_id, invited_by_user_id) VALUES ($lastProjectId, $invitedMemberId, $id_user)";
                mysqli_query($con, $insertMemberQuery);

                // Prepare and send a notification to the invited user
                $notificationMessage = "$username has added you to the project: $project";
				$SQL_insert_notification = "INSERT INTO `notifications` (user_id, inviter_username, project_id, message) VALUES ('$invitedMemberId', '$username', '$lastProjectId', '$notificationMessage')";
				mysqli_query($con, $SQL_insert_notification);
            }
        }
    }

    $success = "Successfully added the project and sent notifications.";
    print "<script>self.location='main.php';</script>";
} else {
    // Handle the case where project insertion failed
    // Display an error message or perform appropriate actions
}
}

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
</style>
</head>
<body class="w3-biru">

<!-- Side Navigation -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-blue w3-card" style="z-index:3;width:250px;" id="mySidebar">
	<a href="main.php" class="w3-bar-item w3-large"><img src="images/logo2.png" class="w3-padding" style="width:150px;"></a>
	<a href="javascript:void(0)" onclick="w3_close()" title="Close Sidemenu" 
	class="w3-bar-item w3-button w3-hide-large w3-large">Close <i class="fa fa-remove"></i></a>
	
	<div class="w3-padding"></div>
	
	<a href="main.php" class="w3-bar-item w3-button w3-round-xxlarge w3-white w3-text-blue">
	<i class="fa fa-fw fa-tachometer-alt w3-margin-right"></i> Dashboard</a>

	<a href="tasks.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="far fa-fw fa-check-circle w3-margin-right"></i> Tasks</a>	
	
	<a href="notification.php" class="w3-bar-item w3-button  w3-round-xxlarge">
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





<div class="w3-container w3-content" style="max-width:1200px;"> 
	<div class="w3-xlarge w3-text-gray">Hi, <?PHP echo $username;?></div>
	<div class="w3-padding">
		<a onclick="document.getElementById('add01').style.display='block'; " class="w3-button w3-blue w3-round-xxlarge"><i class="fa fa-fw fa-plus-circle fa-lg "></i> New Project</a>
	</div>
		
	<div class="w3-padding w3-xlarge"><b>My Projects</b></div>
</div>
	
<div class="w3-container">

	<!-- Page Container -->
	<div class="w3-containerx w3-content  w3-padding-16 " style="max-width:1200px;">    
		<!-- The Grid -->
		<div class="w3-row ">
						
			
			<?PHP
			$tot_task = 0;
			$SQL_list = "SELECT * FROM `project` WHERE `id_user` = $id_user ";
			$SQL_list = "SELECT DISTINCT p.id_project, p.project, p.color FROM `project` p
			LEFT JOIN `project_participants` pp ON p.id_project = pp.project_id
			WHERE pp.member_id = $id_user OR p.id_user = $id_user";
			$result = mysqli_query($con, $SQL_list) ;
			while ( $data = mysqli_fetch_array($result) )
			{
				$id_project = $data["id_project"];
				$color = $data["color"];
				$tot_task 	= numRows($con, "SELECT * FROM `project_task` WHERE `id_project` = $id_project");
			
			    if (isset($_GET['delete_project']) && $_GET['delete_project'] == $id_project) {
					// Execute query to delete the project from the database
					$deleteQuery = "DELETE FROM `project` WHERE `id_project` = $id_project";
					mysqli_query($con, $deleteQuery);
					// Redirect back to main.php after deleting the project to avoid resubmission
					header("Location: main.php");
					exit(); // Ensure script stops execution after redirection
				}
			?>
<a href="project-task.php?id_project=<?php echo $id_project; ?>">
        <div class="w3-col m3 w3-container w3-padding">
            <div class="w3-hover-border-blue w3-hover-white w3-border w3-<?php echo $color; ?> w3-round-xlarge w3-margin w3-padding w3-padding-16" style="position: relative;">
                <div class="w3-padding-small"><i class="fa fa-fw fa-users fa-2x w3-right "></i></div>
                <div class="w3-padding-24"></div>
                <div class="w3-xlarge"><b><?php echo $data["project"]; ?></b></div>
                <div class=""><?php echo $tot_task; ?> Tasks</div>
                <!-- Delete icon -->
                <a href="main.php?delete_project=<?php echo $id_project; ?>" onclick="return confirm('Are you sure you want to delete this project?');" style="position: absolute; bottom: 5px; right: 5px;">
                    <i class="fa fa-trash" style="font-size: 20px;"></i>
                </a>
            </div>
        </div>
    </a>
<?php } ?>
			
		<!-- End Grid -->
		</div>
	  
	<!-- End Page Container -->
	</div>
	
	
	

</div>
<!-- container end -->


	

<div class="w3-padding-24"></div>

     
</div>


<div id="add01" class="w3-modal" >
    <div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px">
      <header class="w3-container "> 
        <span onclick="document.getElementById('add01').style.display='none'" 
        class="w3-button w3-large w3-circle w3-display-topright "><i class="fa fa-fw fa-times"></i></span>
      </header>
	  
      <div class="w3-container w3-padding">
		
		<form action="" method="post" >
			<div class="w3-padding"></div>
			<b class="w3-large">Create Project</b>
			<hr>

				<div class="w3-section" >
					<label>Project Name *</label>
					<input class="w3-input w3-border w3-round" type="text" name="project" max="100" value="" required>
				</div>

				<div class="w3-section">
        			<label>Invite Team (Separate usernames by comma) </label>
        			<input class="w3-input w3-border w3-round" type="text" name="invited_members" max="100">
    			</div>
				
				<div class="w3-section" >
					<label>Color *</label><br>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-white w3-border">
						<input class="w3-radio" type="radio" name="color" value="white" required>
						<label>White</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-red">
						<input class="w3-radio" type="radio" name="color" value="red">
						<label>Red</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-pink">
						<input class="w3-radio" type="radio" name="color" value="pink">
						<label>Pink</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-purple">
						<input class="w3-radio" type="radio" name="color" value="purple">
						<label>Purple</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-blue">
						<input class="w3-radio" type="radio" name="color" value="blue">
						<label>Blue</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-indigo">
						<input class="w3-radio" type="radio" name="color" value="indigo">
						<label>Indigo</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-teal">
						<input class="w3-radio" type="radio" name="color" value="teal">
						<label>Teal</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-green">
						<input class="w3-radio" type="radio" name="color" value="green">
						<label>Green</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-lime">
						<input class="w3-radio" type="radio" name="color" value="lime">
						<label>Lime</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-yellow">
						<input class="w3-radio" type="radio" name="color" value="yellow">
						<label>Yellow</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-amber">
						<input class="w3-radio" type="radio" name="color" value="amber">
						<label>Amber</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-orange">
						<input class="w3-radio" type="radio" name="color" value="orange">
						<label>Orange</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-brown">
						<input class="w3-radio" type="radio" name="color" value="brown">
						<label>Brown</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-light-gray">
						<input class="w3-radio" type="radio" name="color" value="light-gray">
						<label>Light Gray</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-gray">
						<input class="w3-radio" type="radio" name="color" value="gray">
						<label>Gray</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-dark-gray">
						<input class="w3-radio" type="radio" name="color" value="dark-gray">
						<label>Dark Gray</label>
					</div>
					<div class="w3-round-xlarge w3-tag w3-padding-small w3-black">
						<input class="w3-radio" type="radio" name="color" value="black">
						<label>Black</label>
					</div>
				</div>
								  
				<hr class="w3-clear">
			  
				<div class="w3-section" >
					<input name="act" type="hidden" value="add">
					<button type="submit" class="w3-button w3-blue w3-text-white w3-margin-bottom w3-round">SUBMIT</button>
				</div>
			</div>  
		</form> 
         
      </div>
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
<?php
ob_end_flush(); // Flush the output buffer
?>