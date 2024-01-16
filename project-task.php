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
$username	= $_SESSION["username"];

$id_project = (isset($_GET['id_project'])) ? trim($_GET['id_project']) : '0';

$act 		= (isset($_REQUEST['act'])) ? trim($_REQUEST['act']) : '';	

$id_task	= (isset($_REQUEST['id_task'])) ? trim($_REQUEST['id_task']) : '0';

$task		= (isset($_POST['task'])) ? trim($_POST['task']) : '0';
$due_date	= (isset($_POST['due_date'])) ? trim($_POST['due_date']) : '0';
$status		= (isset($_POST['status'])) ? trim($_POST['status']) : '';

$task		=	mysqli_real_escape_string($con, $task);

$success = "";

if($act == "edit")
{	
	$SQL_insert = " 
	UPDATE `project_task` SET `status` = '$status' WHERE id_task = $id_task
	";		
										
	$result = mysqli_query($con, $SQL_insert);
	
	$success = "Successfully Update";
	print "<script>self.location='project-task.php?id_project=$id_project';</script>";
}

if($act == "add")
{	
	$SQL_insert = " 
	INSERT INTO `project_task`(`id_task`, `id_project`, `id_user`, `task`, `due_date`, `status`) 
					VALUES (NULL, '$id_project', '$id_user', '$task', '$due_date', '$status')
	";		
										
	$result = mysqli_query($con, $SQL_insert);
	
	$success = "Successfully Add";
	print "<script>self.location='project-task.php?id_project=$id_project';</script>";
}

if ($act == "del") {
    $SQL_delete = "DELETE FROM `project_task` WHERE `id_task` = '$id_task'";
    $result = mysqli_query($con, $SQL_delete);

    // Redirect to tasks.php after deleting the task
    print "<script>self.location='tasks.php';</script>";
}

$SQL_list 	= "SELECT * FROM `project` WHERE `id_project` = $id_project ";
$result 	= mysqli_query($con, $SQL_list) ;
$data 		= mysqli_fetch_array($result);
$project 	= $data["project"];

$SQL_list = "SELECT * FROM `user` WHERE `id_user`='$id_user' ";
$result = mysqli_query($con, $SQL_list);
$data = mysqli_fetch_array($result);
$name = $data["name"];
$photo = $data["photo"];
if (!$photo) $photo = "noimage.png";

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
<body class="">

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
	
	<a href="notification.php" class="w3-bar-item w3-button w3-round-xxlarge">
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
    <button class="w3-button"><img src="upload/<?php echo $photo; ?>" class="w3-circle" style="width:30px"> <?php echo $_SESSION["username"]; ?> <i class="fa fa-fw fa-chevron-down w3-small"></i></button>
    <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="profile.php" class="w3-bar-item w3-button"><i class="fa fa-fw fa-user-cog "></i> Profile</a>
        <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-fw fa-sign-out-alt "></i> Signout</a>
    </div>
</div>

</div>



<div class="w3-padding-16"></div>

<div class="w3-container w3-content" style="max-width:1200px;"> 
	<div class="w3-padding">
		<a onclick="document.getElementById('add01').style.display='block'; " class="w3-button w3-blue w3-round-xxlarge"><i class="fa fa-fw fa-plus-circle fa-lg "></i> New Task</a>
		<a onclick="document.getElementById('inviteTeam').style.display='block'; " class="w3-button w3-blue w3-round-xxlarge"><i class="fa fa-fw fa-plus-circle fa-lg "></i> Invite Team</a>
	</div>
		
	<div class="w3-padding w3-xlarge"><b><?PHP echo $project;?></b></div>
</div>

	
<div class="w3-container ">

	<!-- Page Container -->
	<div class="w3-containerx w3-content w3-padding-16 " style="max-width:1200px;">    
		<!-- The Grid -->
		<div class="w3-row w3-padding">
									
			<div class="w3-round-xxlarge w3-cardx  w3-border w3-padding w3-padding-16">
			<div class="w3-round-xlarge w3-dark-grey w3-padding">
    <div class="w3-row">
        <div class="w3-col m4">Tasks</div>
        <div class="w3-col m2 w3-center">Due Date</div>
        <div class="w3-col m2 w3-center">Assignee</div>
        <div class="w3-col m2 w3-center">Status</div>
        <div class="w3-col m2 w3-center">Action</div>
    </div>
</div>
</div>
								
				<?PHP
				$bil=0;
				$SQL_list = "SELECT id_task, task, due_date, status FROM `project_task` WHERE `id_project` = $id_project ";
				$result = mysqli_query($con, $SQL_list) ;
				while ( $data = mysqli_fetch_array($result) )
				{
					$bil++;
					$id_task = $data["id_task"];
				?>
     <div class="w3-padding">
        <div class="w3-row w3-padding w3-border-bottom">
            <div class="w3-col m4"><a href="list-task.php" class="w3-text-blue"><?PHP echo $data["task"]; ?></a></div>
            <div class="w3-col m2 w3-center w3-border-right"><?PHP echo $data["due_date"]; ?></div>
            <div class="w3-col m2 w3-center w3-border-right"><i class="fa fa-user-circle fa-lg"></i></div>
            <div class="w3-col m2 w3-center w3-border-right">
                <div class="w3-round-xlarge w3-tag w3-green"><?PHP echo $data["status"]; ?></div>
            </div>
            <div class="w3-col m2 w3-center">
                <a href="#" onclick="document.getElementById('idEdit<?PHP echo $bil;?>').style.display='block'" class="w3-padding">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="#" onclick="document.getElementById('idDelete<?PHP echo $bil;?>').style.display='block'" class="">
                    <i class="fas fa-trash-alt w3-text-red"></i>
                </a>
            </div>
        </div>
    </div>
				
    <div id="idEdit<?PHP echo $bil; ?>" class="w3-modal" style="z-index:10;">
	<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:500px">
      <header class="w3-container "> 
        <span onclick="document.getElementById('idEdit<?PHP echo $bil; ?>').style.display='none'" 
        class="w3-button w3-large w3-circle w3-display-topright "><i class="fa fa-fw fa-times"></i></span>
      </header>

		<div class="w3-container w3-padding w3-margin">
		
		<form action="" method="post"  >
			<div class="w3-padding"></div>
			<b class="w3-large">Update </b>
			<hr>
				
				<div class="w3-section" >
					Status
					<select class="w3-select w3-border w3-round w3-padding" name="status" required>
						<option value="">- Select status - </option>
						<option value="New" <?PHP if($data["status"] == "New") echo "selected"; ?>>New</option>
						<option value="In Progress" <?PHP if($data["status"] == "In Progress") echo "selected"; ?>>In Progress</option>
						<option value="Completed" <?PHP if($data["status"] == "Completed") echo "selected"; ?>>Completed</option>
					</select>
				</div>

				<hr class="w3-clear">
				<input type="hidden" name="id_task" value="<?PHP echo $data["id_task"];?>" >
				<input type="hidden" name="act" value="edit" >
				<button type="submit" class="w3-button w3-blue w3-margin-bottom w3-round">SAVE CHANGES</button>

		</form>
		</div>
	</div>
</div>

<div id="idDelete<?PHP echo $bil; ?>" class="w3-modal" style="z-index:10;">
	<div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:500px">
      <header class="w3-container "> 
        <span onclick="document.getElementById('idDelete<?PHP echo $bil; ?>').style.display='none'" 
        class="w3-button w3-large w3-circle w3-display-topright "><i class="fa fa-fw fa-times"></i></span>
      </header>

		<div class="w3-container w3-padding">
		
		<form action="" method="post" >
			<div class="w3-padding"></div>
			<b class="w3-large">Delete Confirmation</b>
			  
			<hr class="w3-clear">
			
			Are you sure to delete this record?
			
			<div class="w3-padding-16"></div>
			
			<input type="hidden" name="id_task" value="<?PHP echo $data["id_task"];?>" >
			<input type="hidden" name="act" value="del" >
			<button type="button" onclick="document.getElementById('idDelete<?PHP echo $bil; ?>').style.display='none'"  class="w3-button w3-blue w3-margin-bottom w3-round">NO</button>
			
			<button type="submit" class="w3-right w3-button w3-red w3-margin-bottom w3-round">YES, DELETE</button>

		</form>
		</div>
	</div>
</div>				
				<?PHP } ?>

			</div>
			
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
			<b class="w3-large">Add Task</b>
			<hr>

				<div class="w3-section" >
					<label>Task *</label>
					<input class="w3-input w3-border w3-round" type="text" name="task" max="100" value="" required>
				</div>
				
				<div class="w3-section" >
					<label>Due Date *</label>
					<input class="w3-input w3-border w3-round" type="date" name="due_date" value="" required>
				</div>
				
				<div class="w3-section" >
					<label>Status *</label>
					<select class="w3-input w3-border w3-round" name="status" required>
						<option value="New">New</option>
						<option value="In Progress">In Progress</option>
						<option value="Completed">Completed</option>
					</select>
				</div>
				
								  
				<hr class="w3-clear">
			  
				<div class="w3-section" >
					<input name="id_project" type="hidden" value="<?PHP echo $id_project;?>">
					<input name="act" type="hidden" value="add">
					<button type="submit" class="w3-button w3-blue w3-text-white w3-margin-bottom w3-round">SUBMIT</button>
				</div>
			</div>  
		</form> 
         
      </div>
<div class="w3-padding-24"></div>
</div>

<div id="inviteTeam" class="w3-modal">
    <div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:600px">
        <header class="w3-container ">
            <span onclick="document.getElementById('inviteTeam').style.display='none'"
                  class="w3-button w3-large w3-circle w3-display-topright "><i class="fa fa-fw fa-times"></i></span>
        </header>
        <div class="w3-container w3-padding">
            <b class="w3-large">Invite Team</b>
            <hr>
            <div class="w3-section" id="memberList">
    <!-- Display existing team members -->
    <?php

    // Form for inviting team members
    if (isset($_POST['invite_members'])) {
        $invitedMembers = isset($_POST['invited_members']) ? trim($_POST['invited_members']) : '';
        if (!empty($invitedMembers)) {
            // Split the invited members by comma
            $invitedMembersArray = explode(',', $invitedMembers);
    
            foreach ($invitedMembersArray as $invitedMember) {
                $invitedMember = trim($invitedMember);
                // Check if the invited member exists
                $checkMemberQuery = "SELECT id_user FROM `user` WHERE `username` = '$invitedMember'";
                $memberResult = mysqli_query($con, $checkMemberQuery);
                $memberData = mysqli_fetch_array($memberResult);
    
                if ($memberData) {
                    $invitedMemberId = $memberData['id_user'];
                    // Insert the invited member into the project_participants table
                    $insertMemberQuery = "INSERT INTO project_participants (project_id, member_id, invited_by_user_id) VALUES ($id_project, $invitedMemberId, $id_user)";
                    mysqli_query($con, $insertMemberQuery);
    
                    // Prepare and send a notification to the invited user
                    $notificationMessage = "$username has invited you to join the team in the project: $project";
                    $SQL_insert_notification = "INSERT INTO `notifications` (user_id, inviter_username, project_id, message) VALUES ('$invitedMemberId', '$username', '$id_project', '$notificationMessage')";
                    mysqli_query($con, $SQL_insert_notification);
                }
            }
        }
    }

    $SQL_members = "SELECT u.username AS invited_username, inviter.username AS inviter_username
    FROM user AS u
    JOIN project_participants AS pp ON u.id_user = pp.member_id 
    LEFT JOIN user AS inviter ON pp.invited_by_user_id = inviter.id_user
    WHERE pp.project_id = $id_project";

$result_members = mysqli_query($con, $SQL_members);

while ($member = mysqli_fetch_assoc($result_members)) {
echo '<div class="member">';
echo '<span class="username">' . $member['invited_username'] . '</span>';
if ($member['inviter_username']) {
echo ' (Invited by: ' . $member['inviter_username'] . ')';
}
echo '</div>';
}
?>
</div>
            
            <!-- Add the necessary fields for inviting team members -->
            <form action="" method="post">
                <div class="w3-section">
                    <input class="w3-input w3-border w3-round" type="text" name="invited_members" placeholder="Add Member">
                </div>

                <div class="w3-section">
                    <button type="submit" name="invite_members" class="w3-button w3-blue w3-text-white w3-round">SUBMIT</button>
                </div>
            </form>
        </div>

        <style>
            .member {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .removeMember {
                padding: 2px 5px;
                font-size: 10px;
            }

            .username {
                margin-right: 10px;
            }
        </style>
    </div>
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
