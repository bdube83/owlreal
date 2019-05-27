<?php
// if form not yet submitted
// display form
if (!isset($_POST['submit']) && !isset($_GET['register'] )) {
$email = (isset($_COOKIE['name'])) ? $_COOKIE['name'] : '';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Project 9-3: Building A Better Login Form</title>
	</head>
	<body>
		Login
		<p>
		<form method="post" action="responsiveHTML5BlogLogIn.php">
			E-mail: <br />
			<input type="text" name="email" value="<?php echo $email; ?>"/>
			<p>
			Password: <br />
			<input type="password" name="password" />
			<p>
			<input type="checkbox" name="sticky" checked />
			Remember me
			<p>
			<input type="submit" name="submit" value="Log In" />
		</form>
		<div>
			Register to use Up-lifter
			<p>				
			<form method="get" action="responsiveHTML5BlogLogIn.php">
				Name: <br />
				<input type="text" name="username_reg"/>
				<p>				
				E-mail: <br />
				<input type="text" name="email_reg"/>
				<p>
				Create a new password: <br />
				<input type="password" name="password_reg1" />
				<p>
				Re-enter password: <br />
				<input type="password" name="password_reg2" />
				<p>
				<input type="submit" name="register" value="Register" />
			</form>
		</div>
	</body>
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
			session_start();
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