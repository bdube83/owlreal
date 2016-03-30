<?php
	session_start();
	if (!isset($_SESSION['email'])) {
		header('Location: responsiveHTML5BlogLogIn.php');
	}
?>