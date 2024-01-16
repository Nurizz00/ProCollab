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

$name		= (isset($_POST['name'])) ? trim($_POST['name']) : '';
$username	= (isset($_POST['username'])) ? trim($_POST['username']) : '';
$phone		= (isset($_POST['phone'])) ? trim($_POST['phone']) : '';
$email		= (isset($_POST['email'])) ? trim($_POST['email']) : '';
$password	= (isset($_POST['password'])) ? trim($_POST['password']) : '';


$success = "";

if($act == "edit")
{	
	$SQL_update = " 
	UPDATE
		`user`
	SET
		`name` = '$name',
		`phone` = '$phone',
		`username` = '$username',
		`email` = '$email',
		`password` = '$password'
	WHERE
		`id_user`='$id_user' 
		";
	
	$result = mysqli_query($con, $SQL_update);
	
	if(isset($_FILES['photo'])){		 
		  $file_name = $_FILES['photo']['name'];
		  $file_size = $_FILES['photo']['size'];
		  $file_tmp = $_FILES['photo']['tmp_name'];
		  $file_type = $_FILES['photo']['type'];
		  
		  $fileNameCmps = explode(".", $file_name);
		  $file_ext = strtolower(end($fileNameCmps));
		  
		  if(empty($errors)==true) {
			 move_uploaded_file($file_tmp,"upload/".$file_name);
			 
			$query = "UPDATE `user` SET `photo`='$file_name' WHERE `id_user` = '$id_user'";			
			$result = mysqli_query($con, $query) or die("Error in query: ".$query."<br />".mysqli_error($con));
		  }else{
			 print_r($errors);
		  }  
	}
	
	$_SESSION["username"] = $username;
	
	$success = "Successfully Registered";
	//print "<script>self.location='a-profile.php';</script>";
}

// Handle photo removal
if ($act == "remove_photo") {
    $query = "UPDATE `user` SET `photo`='' WHERE `id_user` = '$id_user'";
    $result = mysqli_query($con, $query) or die("Error in query: " . $query . "<br />" . mysqli_error($con));
    // You can add a success message if needed
}

$SQL_list = "SELECT * FROM `user` WHERE `id_user`='$id_user' ";
$result = mysqli_query($con, $SQL_list) ;
$data	= mysqli_fetch_array($result);
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

<div class="w3-padding-16"></div>

<div class="w3-container w3-content" style="max-width:800px;"> 
	<div class="w3-xxlarge"><b>Profile</b></div>
</div>
	
<div class="w3-padding"></div>
	
<div class="w3-container">

	<!-- Page Container -->
	<div class="w3-container w3-white w3-content w3-border w3-round-xlarge w3-cardx w3-padding-16" style="max-width:800px;">    
	  <!-- The Grid -->
	  <div class="w3-row w3-padding">
		
		<?PHP 
		if($success) { ?>
			<div class="w3-panel w3-center w3-green w3-display-container w3-animate-zoom">
			  <p>You profile successfully updated.</p>
			</div>
		<?PHP } ?>
		<form action="" method="post" enctype = "multipart/form-data">

				<div class="w3-row">
					<div class="w3-col m7 w3-container">
						<div class="w3-section " >
							Full Name *
							<input class="w3-input w3-border w3-round" type="text" name="name" value="<?PHP echo $data["name"];?>" placeholder="Enter user name" required>
						</div>
						
						<div class="w3-section " >
							Nickname
							<input class="w3-input w3-border w3-round" type="text" name="username" value="<?PHP echo $data["username"];?>" placeholder="" >
						</div>

						<div class="w3-section " >
							Phone
							<input class="w3-input w3-border w3-round" type="text" name="phone" value="<?PHP echo $data["phone"];?>" placeholder="" >
						</div>
						
						<div class="w3-section " >
							Email
							<input class="w3-input w3-border w3-round" type="email" name="email" value="<?PHP echo $data["email"];?>" placeholder="" >
						</div>

						<div class="w3-section " >
							Password
							<input class="w3-input w3-border w3-round" type="password" name="password" value="<?PHP echo $data["password"];?>" placeholder="" >
						</div>										

					</div>
					
					
					<div class="w3-col m5 w3-container">
    <div class="w3-section w3-center">
        <img src="upload/<?PHP echo $photo; ?>" class="w3-circle w3-image" alt="image" style="width:100%;max-width:200px">
        <?PHP if($data["photo"] <>"") { ?>
            <br>
        <?PHP }  ?>
    </div>

    <div class="w3-section">
        <?PHP if($data["photo"] =="") { ?>
            <div class="custom-file">
                <input type="file" class="w3-input w3-border w3-round-large" name="photo" id="photo" accept=".jpeg, .jpg, .png, .gif">
                <small>  only JPEG, JPG, PNG, or GIF allowed </small>
            </div>
        <?PHP } ?>
    </div>

    <!-- Add remove button directly within the image container -->
    <?php if ($data["photo"] <> "") { ?>
        <div class="w3-section w3-center">
		<a class="w3-button w3-red w3-round w3-small" href="?act=remove_photo"><small>Remove Photo</small></a>
        </div>
    <?php } ?>
</div>
				</div>	
				
					<hr class="w3-clear">
					<input type="hidden" name="id_user" value="<?PHP echo $data["id_user"];?>" >
					<input type="hidden" name="act" value="edit" >
					<button type="submit" class="w3-button w3-blue w3-margin-bottom w3-round">UPDATE</button>
			    
		</form>


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
