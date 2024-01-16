<?PHP
session_start();
require_once("database.php");
$act = (isset($_POST['act'])) ? trim($_POST['act']) : '';

$error = "";

$act 		= (isset($_POST['act'])) ? trim($_POST['act']) : '';
$name		= (isset($_POST['name'])) ? trim($_POST['name']) : '';
$username	= (isset($_POST['username'])) ? trim($_POST['username']) : '';
$phone		= (isset($_POST['phone'])) ? trim($_POST['phone']) : '';
$email		= (isset($_POST['email'])) ? trim($_POST['email']) : '';
$password	= (isset($_POST['password'])) ? trim($_POST['password']) : '';

$error = "";
$success = false;


// Function to validate the password complexity
function validatePassword($password) {
    // Define the regular expressions for password complexity
    $uppercase = preg_match('@[A-Z]@', $password); // At least one uppercase letter
    $lowercase = preg_match('@[a-z]@', $password); // At least one lowercase letter
    $number = preg_match('@[0-9]@', $password);    // At least one number
    $specialChars = preg_match('@[^\w]@', $password); // At least one special character

    // Minimum password length check (change 6 to the desired minimum length)
    $minLength = strlen($password) >= 6;

    // Return true if all conditions are met, false otherwise
    return $uppercase && $lowercase && $number && $specialChars && $minLength;
}

if ($act == "register") {
    $found = numRows($con, "SELECT * FROM `user` WHERE `email` = '$email' ");
    if ($found) {
        $error = "Email already registered";
    } else {
        // Check if the entered username already exists
        $usernameExists = numRows($con, "SELECT * FROM `user` WHERE `username` = '$username'");
        if ($usernameExists) {
            $error = "Username already exists. Please choose a different one.";
        } else {
            // Validate password complexity
            if (!validatePassword($password)) {
                $error = "Password does not meet complexity requirements. Please ensure it has at least 6 characters, includes uppercase and lowercase letters, numbers, and special characters.";
            } else {
                // Proceed with user registration as the username is unique and the password is valid
                $SQL_insert = " 	
                    INSERT INTO `user`(`id_user`, `name`, `username`, `phone`, `email`, `password`, `photo`) 
                    VALUES (NULL, '$name', '$username', '$phone', '$email', '$password', '') ";

                $result = mysqli_query($con, $SQL_insert);
                $success = true;
            }
        }
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

<?PHP if($success) { ?>
<div class="w3-panel w3-center w3-blue w3-display-container w3-animate-zoom">
  <h3>Congratulation!</h3>
  <p>Your registration has been successful!<br>You can now <a href="login.php">Login.</a> </p>
</div>
<?PHP  } else { ?>

			<div class="w3-xxlarge"><b>REGISTRATION</b></div>
			<form action="" method="post">
			<?PHP if($error) { ?>			
			<div class="w3-container w3-padding-32" id="contact">
				<div class="w3-content w3-container w3-red w3-round w3-card" style="max-width:600px">
					<div class="w3-padding w3-center">
					<h3>Error! </h3>
					<p><?PHP echo $error;?></p>
					</div>
				</div>
			</div>	
			<?PHP } ?>
			

				<div class="w3-section" >
					<input class="w3-input w3-border w3-round" type="text" name="name" placeholder="Full Name" required>
				</div>
				
				<div class="w3-section" >
					<input class="w3-input w3-border w3-round" type="text" name="username" placeholder="Nick Name" required>
				</div>
				  
				<div class="w3-section" >
					<input class="w3-input w3-border w3-round" type="phone" name="phone" placeholder="Mobile Phone" required>
				</div>
				  
				<div class="w3-section" >
					<input class="w3-input w3-border w3-round" type="email" name="email" placeholder="Email" required>
				</div>			  
				  
				<div class="w3-section">
					<input class="w3-input w3-border w3-round cpwdx" type="password" name="password" id="password" placeholder="Password (must at least be 6 characters)" required>					
				</div>
				  
				<div class="w3-section">
					<input class="w3-input w3-border w3-round cpwdx" type="password" name="repassword" id="repassword" placeholder="Confirm Password" required>
					<div class="w3-padding "><input type="checkbox" onclick="myFunction()"> Show Password</div>
				</div>
				  
				  <script>
					function myFunction() {
					  var x = document.getElementById("password");
					  var y = document.getElementById("repassword");
					  if (x.type === "password") {
						x.type = "text";
						y.type = "text";
					  } else {
						x.type = "password";
						y.type = "password";
					  }
					}
					</script>
	
				<input name="act" type="hidden" value="register">
				<button type="submit" class="w3-button w3-block w3-padding w3-blue w3-margin-bottom w3-round-large">SUBMIT</button>
			</form>
<?PHP  } ?>	
			
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
