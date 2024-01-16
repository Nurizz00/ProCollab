<?PHP
session_start();
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
			<div class="w3-padding-48"></div>
			<div class="w3-xxxlarge"><b>IT'S TIME TO GET ORGANIZED.</b></div>
			<div class="w3-padding-16"></div>
			<div class="w3-large w3-text-gray">Experience a new level of efficiency and coordination in your group assignment</div>
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
