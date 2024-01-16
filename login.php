<?PHP
session_start();
require_once("database.php");
$act = (isset($_POST['act'])) ? trim($_POST['act']) : '';

$error = "";
$success = false;

if($act == "login") 
{
	$email 	= (isset($_POST['email'])) ? trim($_POST['email']) : '';
	$password 	= (isset($_POST['password'])) ? trim($_POST['password']) : '';

	$SQL_login = " SELECT * FROM `user` WHERE `email` = '$email' AND `password` = '$password'  ";

	$result = mysqli_query($con, $SQL_login);
	$data	= mysqli_fetch_array($result);

	$valid = mysqli_num_rows($result);

	if($valid > 0)
	{
		$_SESSION["email"] = $email;
		$_SESSION["password"] = $password;
		$_SESSION["id_user"] = $data["id_user"];
		$_SESSION["username"] = $data["username"];
		$success =  true;
		header( "refresh:1;url=main.php" );
	}else{
		$error = "Invalid";
		header( "refresh:1;url=login.php" );
		//print "<script>alert('Login tidak sah!'); self.location='index.php';</script>";
	}
}
?>
<!DOCTYPE html>
<html>
<title>ProCollab</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Poppins", sans-serif}

body, html {
  height: 100%;
  line-height: 1.5;
}

a:link {
  text-decoration: none;
}

/* Full height image header */
.bgimg-1 {
  background-position: top;
  background-size: cover;
  background-attachment:fixed;
  background-image: url(images/banner.jpg);
  min-height:100%;
  background-color: rgba(0, 0, 0, 0.3);
  background-blend-mode: overlay;
}

.w3-bar .w3-button {
  padding: 16px;
}
</style>

<body class="">

<?PHP include("menu.php"); ?>


<div class="w3-row" >
    <div class="w3-col m6 w3-padding" >
		<div class="w3-content" style="max-width:500px">
			<div class="w3-padding-16"></div>
			<a href="index.php"><img src="images/logo.png" class="w3-image" style="width:150px"></a>
			<div class="w3-padding-32"></div>			
			<div class="w3-xxlarge"><b>LOGIN</b></div>
			<?PHP if($success) { ?>
			<div class="w3-panel w3-center w3-green w3-display-container w3-animate-zoom">
			  <h3>Login Successfully!</h3>
			  <p>You will be redirected to the home page shortly.</p>
			</div>
			<?PHP  }  else { ?>
			<form action="" method="post">
				
				
				
				<?PHP if($error) { ?>			
				<div class="w3-panel w3-center w3-red w3-display-container w3-animate-zoom">
						<h3>Error! Invalid login</h3>
						<p>Please try again...</p>
				</div>	
				<?PHP } ?>
				
				
				<div class="w3-section" >
					<label>Email *</label>
					<input class="w3-input w3-border w3-round" type="email" name="email"  required>
				</div>
				<div class="w3-section">
					<label>Password *</label>
					<input class="w3-input w3-border w3-round" type="password" name="password" required>
				</div>
		
				<input name="act" type="hidden" value="login">
				<button type="submit" class="w3-button w3-block w3-padding-large w3-blue w3-margin-bottom w3-round-large">LOGIN</button>
			</form>
			<?PHP } ?>
		</div>
    </div>
	<div class="w3-col m6" >
		<img src="images/main.png" class="w3-imagex" style="height: 100vh; width:100%">
    </div>
</div>




 
<script>

// Toggle between showing and hiding the sidebar when clicking the menu icon
var mySidebar = document.getElementById("mySidebar");

function w3_open() {
  if (mySidebar.style.display === 'block') {
    mySidebar.style.display = 'none';
  } else {
    mySidebar.style.display = 'block';
  }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
}
</script>

</body>
</html>
