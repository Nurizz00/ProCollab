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
// $id_project = (isset($_GET['id_project'])) ? trim($_GET['id_project']) : '0';
$file_name	= (isset($_POST['file_name'])) ? trim($_POST['file_name']) : '';
$uploaded_by= (isset($_POST['uploaded_by'])) ? trim($_POST['uploaded_by']) : '';
$id_task = (isset($_POST['id_task'])) ? $_POST['id_task'] : '';
$id_project = (isset($_POST['id_project'])) ? $_POST['id_project'] : '';

$success = "";


// $SQL_list 	= "SELECT * FROM `project` WHERE `id_project` = $id_project ";
// $result 	= mysqli_query($con, $SQL_list) ;
// $data 		= mysqli_fetch_array($result);
// $project 	= $data["project"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>ProCollab</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
$serverName = $_SERVER['SERVER_NAME'];
echo "<!-- Server Name: " . $serverName . " -->";
?>
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
      <button class="w3-button"><i class="fa fa-fw fa-user-circle fa-lg"></i> <?PHP echo $_SESSION["username"];?> <i class="fa fa-fw fa-chevron-down w3-small"></i></button>
      <div class="w3-dropdown-content w3-bar-block w3-card-4">
        <a href="profile.php" class="w3-bar-item w3-button"><i class="fa fa-fw fa-user-cog "></i> Profile</a>
        <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-fw fa-sign-out-alt "></i> Signout</a>
      </div>
    </div>

</div>


<div class="w3-padding-16"></div>

<div class="w3-container w3-content" style="max-width:1200px;"> </div>

	
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $file_name = $_FILES["attachment"]["name"];
  $uploaded_by = $_SESSION["username"];
  $id_project = "?"; // Replace with your actual logic to get project ID
  $id_task = "?"; // Replace with your actual logic to get task ID

  $file_name = mysqli_real_escape_string($con, $file_name);
  $uploaded_by = mysqli_real_escape_string($con, $uploaded_by);
  $id_project = mysqli_real_escape_string($con, $id_project);
  $id_task = mysqli_real_escape_string($con, $id_task);

  $insert_query = "INSERT INTO uploaded_files (file_name, uploaded_by, id_project, id_task) VALUES (?, ?, ?, ?)";
  
  $stmt = mysqli_prepare($con, $insert_query);
  mysqli_stmt_bind_param($stmt, "ssii", $file_name, $uploaded_by, $id_project, $id_task);

  if (mysqli_stmt_execute($stmt)) {
      // Move uploaded file to the "uploads" directory
      $targetDirectory = "uploads/";
      $targetFile = $targetDirectory . basename($file_name);

      if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFile)) {
          echo "";
      } else {
          echo "Error uploading file. Check directory permissions.";
      }
  } else {
      echo "Error inserting file details into the database: " . mysqli_error($con);
  }

  mysqli_stmt_close($stmt);
}

?>

<!-- HTML code for the file upload form -->
<div class="w3-container w3-center" style="margin-top: 20px; padding-bottom: 20px; max-width: 900px; margin-left: auto; margin-right: auto;">
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_task" value="<?php echo $id_task; ?>">
    <input type="hidden" name="id_project" value="<?php echo $id_project; ?>">
    <!-- Other form fields -->
    <div class="custom-file">
        <input type="file" class="w3-input w3-border w3-round-large" name="attachment" id="attachment" accept=".pdf, .jpg, .jpeg, .png, .gif, .doc, .docx">
    </div>
    <button type="submit" class="w3-button w3-blue" style="margin-top: 10px;">Upload</button>
</form>
</div>

<!-- Display the list of uploaded files -->
<?php
$files_query = "SELECT * FROM uploaded_files WHERE id_project = ? AND id_task = ?";
$statement = mysqli_prepare($con, $files_query);

// Bind the parameters
mysqli_stmt_bind_param($statement, "ii", $id_project, $id_task);

// Execute the query
mysqli_stmt_execute($statement);

// Get the result
$files_result = mysqli_stmt_get_result($statement);

if (mysqli_num_rows($files_result) > 0) {
  echo "<ul style='list-style: none; padding: 0; text-align: left; margin-left: 70px;'>";

  while ($file_row = mysqli_fetch_assoc($files_result)) {
      $file_name = $file_row['file_name'];

      // Display the file name with an icon
      echo "<li style='margin-bottom: 10px;'>";
      echo "  <a href='uploads/$file_name' target='_blank'>";
      echo "    <i class='fas fa-file'></i> $file_name";
      echo "  </a>";
      echo "</li>";
  }

  echo "</ul>";
} else {
  echo "No files have been uploaded yet.";
}
mysqli_close($con);
?>



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