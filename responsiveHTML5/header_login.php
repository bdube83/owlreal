	<?php
	header("X-Frame-Options: DENY");
	header("Content-Security-Policy: frame-ancestors 'none'", false);

		require_once('chat_login.php');
		//require_once('bongs_error_handler.php');

	?>
<?php
$chat_login = new Chat_login();
?>
<link rel="icon" href="top_comment.png">
<link rel="stylesheet" type="text/css" href="font-awesome-4.3.0/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="header.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<nav>
	<ul>				
		<li>
			<a href="index.php">
				<div>
					<img src="top_comment.png" style="vertical-align:middle;display:inline-block;padding-bottom:3px;" height="25" width="25">
				</div>
			</a>
		</li>
	</ul>
</nav>

<div class="g-signin2" id="googleTop" data-onsuccess="onSignIn" data-width="200" data-height="30" data-theme="dark"></div>
