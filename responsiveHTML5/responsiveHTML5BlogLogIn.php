<?php
// if form not yet submitted
// display form
session_start();
session_destroy();
if (!isset($_POST['submit']) && !isset($_GET['register'] )) {
$email = (isset($_COOKIE['name'])) ? $_COOKIE['name'] : '';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<link rel="icon" href="top_comment.png">
		<title>Login Form</title>
		<link rel="stylesheet" type="text/css" href="responsive-grid.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<!--Google Sign in-->
		<meta name="google-signin-scope" content="profile email">
		<meta name="google-signin-client_id" content="866180971830-ba5bad574898nee2p8mrpvhc255ljc5i.apps.googleusercontent.com">
		<script src="https://apis.google.com/js/platform.js" ></script>
  
	</head>
<body>
<?php
require_once('facebookshare.php');
?>

<header>
	<?php 
		if (file_exists('header_login.php')){
			include('header_login.php');
		}
	?>
</header>

<div class='header_under'></div>

<div class="content" role="main">
	<div class="wrapper">
		<div class="row">
			<aside class="sm-col-span-5 lg-col-span-5">
				<div class="box" >
				<h2>Our Top Titles</h2>
				<p></p>
				<div>
				<?php

					echo $chat_login->getTitleMessages('Top');
				?>
				</div>
				</div>
			</aside>
			<article id="article" class="sm-col-span-4 lg-col-span-2">
				<center><h2>Welcome to ScreenGrap</h2></center>
				<!--<div class="box">-->

				<!--	<img value="" type="image" src="website_images/Step_1.jpg" style="width:98%;height:50%;">-->
				<!--	<img value="" type="image" src="website_images/Step_2.jpg" style="width:98%;height:50%;">-->
				<!--	<img value="" type="image" src="website_images/Step_3.jpg" style="width:98%;height:50%;">-->
					
				<!--	<div class="fb-share-button" data-href="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogLogIn.php" data-layout="button">-->
				<!--	</div>-->
					
				<!--</div>-->
				<div class="box">
					<h2 >Sign in</h2>
					<form  method="post" action="responsiveHTML5BlogLogIn.php">
						<p>Email</br><input name="email" value="<?php echo $email; ?>" required></p>
						<p>Password</br><input name="password" type="password"  required></p>
						<p></br><input type="checkbox" name="sticky" checked />Remember me</p>
						<p><input class="_button" type="submit" name="submit" value="Log In"></p>
					</form>
					
				<h2 >Register</h2>
				<p></p>
					<div class="box" id="boxtest">
						<div id="login">

							<form action="responsiveHTML5BlogLogIn.php" method="get">

							  <div>
								<p>Name</br><input  name="username_reg" maxlength="50" type="text"  required></p> <!-- JS because of IE support; better: placeholder="Username" -->
								<p>Surname</br><input name="surname" maxlength="50" type="text"  required></p> 
								<p>Email</br><input name="email_reg" maxlength="50" type="text" required></p>
								<p>Create a new password</br> <input name="password_reg1" type="password" required></p> <!-- JS because of IE support; better: placeholder="Password" -->
								<p>Re-enter password</br><input name="password_reg2" type="password" required></p>
								<p><input class="_button" type="submit" name="register" value="Register"></p>
								OR
				    			<div class="g-signin2" data-onsuccess="onSignIn" data-width="200" data-height="30" data-theme="dark"></div>
							  </div>

							</form>

						  </div> <!-- end login --> 
					</div>
					
				</div>
				<!--<h2>Post of the day</h2>-->
				<!--<p></p>-->
				<!--<div>-->
				<?php
					// echo $chat_login->getDayMessages(46);
					// echo $chat_login->getDayMessages(45);
					// echo $chat_login->getDayMessages(48);
				?>
				<!--</div>-->

			</article>
			
			<aside class="sm-col-span-4 lg-col-span-1">
				<!--<div class="box" >-->
				<!--	<h1>Location vacant</h1>-->
				<!--	<p>Contact us if you want to claim this space.<p>-->
				<!--	<p>bdube83@gmail.com</p>-->
				<!--</div>-->
				
				<!--<div class="box" >-->
				<!--	<h1>Proudly made in Africa</h1>-->
				<!--	<p>The website is still new, If you incounter any problems please let us know.<p>-->
				<!--	<p>bdube83@gmail.com</p>-->
				<!--</div>-->
				
				<!--<div class="box" >-->
				<!--	<h1>Developers needed</h1>-->
				<!--	<p>We call on all Software developers with a passion for programing to contribute towards the growth of this website.<p>-->
				<!--	<p>bdube83@gmail.com</p>-->
				<!--</div>-->

				
			</aside>
		</div>
	</div>
</div>
</body>

			<!--<center><a style="text-decoration:none; padding:5px;" class="_button" href="#" >more</a></center>-->

</html>
<?php
	// if form submitted
	// check supplied login credentials
	// against database
} else {
	if (isset($_POST['submit'])) {
	require_once('config.php');
	require_once('bongs_error_handler.php');
	$email = $_POST['email'];
	$password = $_POST['password'];
	// check input
	if (empty($email)) {
		die('Please enter your email');
	}
	if (empty($password)) {
		die('Please enter your password');
	}
	// attempt database connection
	$mysqli =new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
	// escape special characters in input
	$email = $mysqli->real_escape_string($email);
	$password = $mysqli->real_escape_string($password);
	// check if emails exists
	$sql = 'SELECT COUNT(*) AS email_count FROM users WHERE email = "'.$email.'"';
	if ($result = $mysqli->query($sql)) {
		if($result->num_rows > 0){
			while($row = $result->fetch_array(MYSQLI_ASSOC)){
				// if yes, fetch the encrypted password
				if ($row['email_count'] == 1) {
					$sql = 'SELECT password FROM users WHERE email = "'.$email.'"';
					// encrypt the password entered into the form
					// test it against the encrypted password stored in the database
					// if the two match, the password is correct
					if ($result = $mysqli->query($sql)) {
						if($result->num_rows > 0){
							while($row = $result->fetch_array(MYSQLI_ASSOC)){
								$salt = $row['password'];
								if (crypt($password, $salt) == $salt) {
									$sql = 'SELECT name, iduser FROM users WHERE email = "'.$email.'"';
									if ($result = $mysqli->query($sql)) {
										if($result->num_rows > 0){
											while($row = $result->fetch_array(MYSQLI_ASSOC)){
												// if yes, fetch the user name
												$user_name = $row['name'];
												$iduser = $row['iduser'];
											}
										}
									}
									// password correct
									// start a new session
									// save the email to the session
									// if required, set a cookie with the email
									// redirect the browser to the main application page
									session_start();
									session_destroy();
									session_start();
									
									$_SESSION['email'] = $email;
									$_SESSION['user_name'] = $user_name;
									$_SESSION['iduser'] = $iduser;
									$_SESSION['new_user'] = false;
									$_SESSION['welcome'] = true;
									if (isset($_POST['sticky'])) {
										setcookie('email', $_POST['email'], time()+86400);
									}
									header('Location: index.php');
								} else {
								echo 'You entered an incorrect password.';
								}
							}
						}
					} else {
					echo "ERROR: Could not execute $sql. " ;
					}
				} else {
				echo 'You entered an incorrect email.';
				}
			}
		}
	} else {
	echo "ERROR: Could not execute $sql. " ;
	}
	// close connection
	unset($mysqli);
	//New comers
	}elseif(isset($_GET['register'] )) {
		require_once('config.php');
		require_once('bongs_error_handler.php');
		$username_reg = trim($_GET['username_reg']);
		$email_reg = trim($_GET['email_reg']);
		$password_reg1 = trim($_GET['password_reg1']);
		$password_reg2 = trim($_GET['password_reg2']);
		
		// check input
		if (empty($username_reg)) {
			die('Please enter your name');
		}
		if (empty($email_reg)){
			die('Please enter your email');
		}
		if (empty($password_reg1) || empty($password_reg2)) {
			die('Please enter your password');
		}
		if($password_reg1!=$password_reg2){
			die('The passwords you entered did not match. Please re-enter your password');
		}
		// attempt database connection
		$mysqli =new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		// escape special characters in input
		$username_reg = $mysqli->real_escape_string($username_reg);
		$email_reg = $mysqli->real_escape_string($email_reg);
		$password_reg1 = $mysqli->real_escape_string($password_reg1);
		$password_reg1 = crypt($password_reg1);
		// check if usernames exists
		$sql = 'SELECT COUNT(*) AS email_count FROM users WHERE email = "'.$email_reg.'"';
		if ($result = $mysqli->query($sql)) {
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					// if yes, fetch the encrypted password
					if ($row['email_count'] >= 1) {
						die("The email you entered already exists. Please enter another email");
					}
				}
			}
		}
		$sql = 'INSERT INTO users (name, email, password) '.
				'VALUES ("'.$username_reg.'", "'.$email_reg.'", "'.$password_reg1.'")';
		if ($mysqli->query($sql)) {
			if ($stmt = $mysqli->prepare('SELECT LAST_INSERT_ID()')) {//get last inserted items id
						$stmt->execute();
						$stmt->bind_result($id);
						while ($stmt->fetch()) {
						}
						$stmt->close();
			}
			session_start();
			session_destroy();
			session_start();
			$_SESSION['iduser'] = $id;
			$_SESSION['email'] = $email_reg;
			$_SESSION['user_name'] = $username_reg;
			$_SESSION['new_user'] = true;
			$_SESSION['welcome'] = true;
			header('Location: index.php');
		}else{
			die ("Failed");
		}
		// close connection
		unset($mysqli);
	}
}
?>



  
  <script>
    function getCookie(name) {
      var value = "; " + document.cookie;
      var parts = value.split("; " + name + "=");
      if (parts.length == 2) return parts.pop().split(";").shift();
    }
    
    function createCookie(name, value, days) {
	    var expires;
	
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        expires = "; expires=" + date.toGMTString();
	    } else {
	        expires = "";
	    }
	    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
	}
	
	function eraseCookie(name) {
	    createCookie(name, "", -1);
	}

    function onSignIn(googleUser) {
      if(getCookie('sign_out')){
        revokeAllScopes();
        eraseCookie("sign_out");
        window.location.replace("responsiveHTML5BlogLogIn.php");
        return;
      }

      // Useful data for your client-side scripts:
      var profile = googleUser.getBasicProfile();
      var profileJSON = { 'email'       : profile.getEmail(),
                          'user_name'   : profile.getName(),
                          'google_user'   : "true",
                          'id_token'    : googleUser.getAuthResponse().id_token};/* The ID token you need to pass to your backend: */
      console.log(profileJSON);
      verifyLogin(profileJSON);
    }
    var revokeAllScopes = function() {
        var auth2 = gapi.auth2.getAuthInstance({
            client_id: '866180971830-ba5bad574898nee2p8mrpvhc255ljc5i.apps.googleusercontent.com',
            scope: 'profile'
        });
        console.log(auth2.disconnect());
    }
  </script>

	<script>
	
	  /*function that verifies user is from uwc, regesters the user on the server and re-directs to the booking page if true.
	  *-----------------------------------------------------------------------------------
	  *@param profileJSON : Useful data for your client-side scripts:
	  */
	  function verifyLogin(profileJSON){
	      var email = profileJSON.email;

          jQuery(document).ready(function(){
               // Using JSONP to connect to register_user.php
              $.ajax({
                  url: "https://screengrap-bdube83.c9users.io/responsiveHTML5/register_user.php",
                          
                     //prepering data to send.
                  type: 'POST',
                  data: profileJSON,
                  
                  //contentType: 'application/json; charset=utf-8',
                  
                  // Tell jQuery we're expecting JSON
                  dataType: "json", 
                  
                  
                  // Work with the response
                  success: function( response_login ) {
                      console.log(response_login);
                      if(response_login.report == 'true'){
                          window.location.href = "index.php";//cannot go back.
                          //window.location.href = "transport_booking.php"; //can go back.

                      }else{
                          alert('Please try agian.');
                      }
                  },
                  error: function (request, status, error) {
                      revokeAllScopes();
                      document.getElementById("login").innerHTML = "Oops! Something went wrong, please sign in agian..";
                      console.log(request.responseText);
                      console.log(error);
                      console.log(status);
                  }
              });
              
          });
      }

	</script>