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
$act 		= (isset($_REQUEST['act'])) ? trim($_REQUEST['act']) : '';	

$id_project_find	= (isset($_REQUEST['id_project_find'])) ? trim($_REQUEST['id_project_find']) : '0';

$id_timetable= (isset($_REQUEST['id_timetable'])) ? trim($_REQUEST['id_timetable']) : '0';
$id_project	= (isset($_POST['id_project'])) ? trim($_POST['id_project']) : '0';
$event		= (isset($_POST['event'])) ? trim($_POST['event']) : '';
$date		= (isset($_POST['date'])) ? trim($_POST['date']) : '';
$time		= (isset($_POST['time'])) ? trim($_POST['time']) : '';
$participant= (isset($_POST['participant'])) ? trim($_POST['participant']) : '';
$link		= (isset($_POST['link'])) ? trim($_POST['link']) : '';

$event		=	mysqli_real_escape_string($con, $event);

$success = "";

if($act == "edit")
{	
	$SQL_insert = " 
	UPDATE
		`timetable`
	SET
		`event` = '$event',
		`date` = '$date',
		`time` = '$time',
		`participant` = '$participant',
		`link` = '$link'
	WHERE
		`id_timetable` = '$id_timetable'
	";		
										
	$result = mysqli_query($con, $SQL_insert);
	
	$success = "Successfully Update";
	print "<script>self.location='timetable.php';</script>";
}

if($act == "add")
{	
	$SQL_insert = " 
	INSERT INTO `timetable`(`id_timetable`, `id_project`, `id_user`, `event`, `date`, `time`, `participant`, `link`) 
		VALUES (NULL, '$id_project', '$id_user', '$event', '$date', '$time', '$participant', '$link')
	";		
										
	$result = mysqli_query($con, $SQL_insert);
	
	$success = "Successfully Add";
	print "<script>self.location='timetable.php';</script>";
}

if($act == "del")
{
	$SQL_delete = " DELETE FROM `timetable` WHERE `id_timetable` =  '$id_timetable' ";
	$result = mysqli_query($con, $SQL_delete);
	
	print "<script>self.location='timetable.php';</script>";
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
	
	<a href="main.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="fa fa-fw fa-tachometer-alt w3-margin-right"></i> Dashboard</a>

	<a href="tasks.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="far fa-fw fa-check-circle w3-margin-right"></i> Tasks</a>	
	
	<a href="notification.php" class="w3-bar-item w3-button w3-round-xxlarge">
	<i class="far fa-fw fa-bell w3-margin-right"></i> Notification</a>
	
	<a href="timetable.php" class="w3-bar-item w3-button w3-round-xxlarge w3-white w3-text-blue">
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
	<div class="w3-xlarge"><i class="fas fa-fw fa-calendar w3-text-red"></i> <b>Timetable</b></div>
	<div class="w3-padding">
		<a onclick="document.getElementById('add01').style.display='block'; " class="w3-button w3-blue w3-round-xxlarge"><i class="fa fa-fw fa-plus-circle fa-lg "></i> Add Event</a>
	</div>
</div>

	
<div class="w3-container">

	<!-- Page Container -->
	<div class="w3-containerx w3-content w3-padding-16 " style="max-width:1200px;">    
		<!-- The Grid -->
		<div class="w3-row w3-paddingx">
										
			<div class="w3-row">
			<form action="" method="post">
    <div class="w3-col m3 w3-padding">
        <select class="w3-select w3-border w3-round w3-padding" name="id_project_find">
            <option value="">- All Project - </option>
            <?php
            // Retrieve projects owned by the user
            $sqlOwnedProjects = "SELECT * FROM `project` WHERE id_user = $id_user";
            $resultOwnedProjects = mysqli_query($con, $sqlOwnedProjects);

            while ($dataOwnedProjects = mysqli_fetch_array($resultOwnedProjects)) {
                echo "<option value='{$dataOwnedProjects["id_project"]}' " . ($id_project_find == $dataOwnedProjects["id_project"] ? "selected" : "") . ">{$dataOwnedProjects["project"]}</option>";
            }

            // Retrieve projects where the user has been invited
			$sqlInvitedProjects = "
			SELECT project.id_project, project.project
			FROM project
			INNER JOIN project_participants ON project.id_project = project_participants.project_id
			WHERE project_participants.member_id = $id_user
		";

	$resultInvitedProjects = mysqli_query($con, $sqlInvitedProjects);

	while ($dataInvitedProjects = mysqli_fetch_array($resultInvitedProjects)) {
		echo "<option value='{$dataInvitedProjects["id_project"]}' " . ($id_project_find == $dataInvitedProjects["id_project"] ? "selected" : "") . ">{$dataInvitedProjects["project"]}</option>";
	}
	?>
        </select>
    </div>
    <div class="w3-col m3 w3-padding">
        <button type="submit" class="w3-button w3-blue w3-text-white w3-margin-bottom w3-round">Retrieve</button>
    </div>
</form>
			</div>
			
			<div class="w3-round-xxlarge w3-cardx  w3-border w3-padding w3-padding-16">
				<div class="w3-round-xlarge w3-padding w3-dark-grey">
					<div class="w3-row w3-padding">
						<div class="w3-col m3">Event</div>
						<div class="w3-col m1 w3-center">Date</div>
						<div class="w3-col m1 w3-center">Time</div>
						<div class="w3-col m2 w3-center">Participant</div>
						<div class="w3-col m4 ">Link</div>
						<div class="w3-col m1 w3-center">Action</div>
					</div>
				</div>
								
				<?PHP
				$SQL_project = "";
				
				if($id_project_find) $SQL_project = "AND `id_project` = $id_project_find";
				$bil=0;
				$SQL_list = " SELECT * FROM `timetable` WHERE `id_user` = $id_user $SQL_project
			
				UNION
			
				SELECT t.* FROM `timetable` t
				INNER JOIN `project_participants` pp ON t.id_project = pp.project_id
				WHERE pp.`member_id` = $id_user $SQL_project";

				$result = mysqli_query($con, $SQL_list) ;
				while ( $data = mysqli_fetch_array($result) )
				{
					$bil++;
					$id_timetable = $data["id_timetable"];
				?>
				<div class="w3-padding ">
					<div class="w3-row w3-padding w3-border-bottom">
						<div class="w3-col m3"><a href="#" class="w3-text-blue"><?PHP echo $data["event"]; ?></a></div>
						<div class="w3-col m1 w3-center"><?PHP echo $data["date"]; ?></div>
						<div class="w3-col m1 w3-center"><?PHP echo $data["time"]; ?></div>
						<div class="w3-col m2 w3-center"><i class="fa fa-user-circle fa-lg"></i></div>
						<div class="w3-col m4 "><?PHP echo $data["link"]; ?></div>
						<div class="w3-col m1 w3-center">
						<a href="#" onclick="document.getElementById('idEdit<?PHP echo $bil;?>').style.display='block'" class="w3-padding"><i class="fas fa-edit"></i></a>				
						<a href="#" onclick="document.getElementById('idDelete<?PHP echo $bil;?>').style.display='block'" class=""><i class="fas fa-trash-alt w3-text-red"></i></a>
						</div>
					</div>
				</div>
				
				<div id="idEdit<?PHP echo $bil; ?>" class="w3-modal" style="z-index:10;">
    <div class="w3-modal-content w3-round-large w3-card-4 w3-animate-zoom" style="max-width:500px">
        <header class="w3-container ">
            <span onclick="document.getElementById('idEdit<?PHP echo $bil; ?>').style.display='none'"
                class="w3-button w3-large w3-circle w3-display-topright "><i
                    class="fa fa-fw fa-times"></i></span>
        </header>

        <div class="w3-container w3-padding w3-margin">

		<form action="" method="post" onsubmit="return validateForm()" name="eventForm">
                <div class="w3-padding"></div>
                <b class="w3-large">Update</b>
                <hr>

                <div class="w3-section">
                    <label>Event *</label>
                    <input class="w3-input w3-border w3-round" type="text" name="event" value="<?PHP echo $data["event"]; ?>"
                        required>
                </div>

                <div class="w3-section">
                    <label>Date *</label>
                    <input class="w3-input w3-border w3-round" type="date" name="date"
                        value="<?PHP echo $data["date"]; ?>" required>
                </div>

                <div class="w3-section">
                    <label>Time *</label>
                    <input class="w3-input w3-border w3-round" type="time" name="time"
                        value="<?PHP echo $data["time"]; ?>" required>
                </div>

                <div class="w3-section">
                    <label>Participant *</label>
                    <input class="w3-input w3-border w3-round" type="text" name="participant"
                        value="<?PHP echo $data["participant"]; ?>" required>
                </div>

				<div class="w3-section">
					<label>Link Meeting *</label>
					<input class="w3-input w3-border w3-round" type="text" name="link" value="" placeholder="Google Meet link" required>
					<small>Example: https://meet.google.com/abc-def-ghi</small>
				</div>


                <hr class="w3-clear">

                <input type="hidden" name="id_timetable" value="<?PHP echo $data["id_timetable"]; ?>" >
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
			
			<input type="hidden" name="id_timetable" value="<?PHP echo $data["id_timetable"];?>" >
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
		
	  <form action="" method="post" onsubmit="return validateForm()" name="eventForm">
			<div class="w3-padding"></div>
			<b class="w3-large">Add Event</b>
			<hr>

			<div class="w3-section">
    <label>Project *</label>
    <select class="w3-select w3-border w3-round w3-padding" name="id_project" required>
        <option value="">- Select Project - </option>
        <?php 
            // Retrieve projects owned by the user
            $sqlOwnedProjects = "SELECT * FROM `project` WHERE id_user = $id_user";
            $resultOwnedProjects = mysqli_query($con, $sqlOwnedProjects);
            
            while ($dataOwnedProjects = mysqli_fetch_array($resultOwnedProjects)) {
                echo "<option value='{$dataOwnedProjects["id_project"]}'>{$dataOwnedProjects["project"]}</option>";
            }

            // Retrieve projects where the user has been invited
            $sqlInvitedProjects = "
                SELECT project.id_project, project.project
                FROM project
                INNER JOIN project_participants ON project.id_project = project_participants.project_id
                WHERE project_participants.member_id = $id_user
            ";

            $resultInvitedProjects = mysqli_query($con, $sqlInvitedProjects);

            while ($dataInvitedProjects = mysqli_fetch_array($resultInvitedProjects)) {
                echo "<option value='{$dataInvitedProjects["id_project"]}'>{$dataInvitedProjects["project"]}</option>";
            }
        ?>
    </select>
</div>
				
				<div class="w3-section" >
					<label>Event *</label>
					<input class="w3-input w3-border w3-round" type="text" name="event" max="100" value="" required>
				</div>
				
				<div class="w3-section" >
					<label>Date *</label>
					<input class="w3-input w3-border w3-round" type="date" name="date" value="" required>
				</div>
				
				<div class="w3-section" >
					<label>Time *</label>
					<input class="w3-input w3-border w3-round" type="time" name="time" value="" required>
				</div>
				
				<div class="w3-section">
					<label>Link Meeting *</label>
					<input class="w3-input w3-border w3-round" type="text" name="link" value="" placeholder="Google Meet link" required>
					<small>Example: https://meet.google.com/abc-def-ghi</small>
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
