<?php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);

$chat_init = new Chat_init();
?>

<link rel="stylesheet" type="text/css" href="https://screengrap-bdube83.c9users.io/responsiveHTML5/font-awesome-4.3.0/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="https://screengrap-bdube83.c9users.io/responsiveHTML5/header.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<nav>
	<ul>				
		<li>
			<a>
				<div>
					<i class="fa fa-bars"></i>
				</div>
			</a
			><div>
				<ul>
					<li><a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogWall.php">My Profile</a></li>
					<li><a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5TopTitles.php">Top Titles</a></li>
					<li><a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5NewTitles.php">New Titles</a></li>
					<li><a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogPrivateWall.php">My Private Wall</a></li>
					<li><a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/logoff.php">Log-off</a></li>
					
				</ul>
			</div>
		</li>
		<li>
			<a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/index.php">
				<div>
					ScreenGrap
				</div>
			</a>
		</li>
	</ul>
</nav>


