<?php
require_once('check_login_header.php');
?>
<!DOCTYPE html>
<html>
<style>
#article0{
	line-height: 1.5em;
	font-size: 12px;
	font-family: arial;
}
</style>
<head>
	<title>Responsive Grid</title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="responsive-grid.css">
	<script src="chatcom.js" type="text/javascript"  ></script>
	<?php
		require_once('chat_init.php');
		require_once('chat.class.php');
		$chat = new Chat();
		//require_once('bongs_error_handler.php');

	?>

</head>
<body  >
<header>
	<?php 
		if (file_exists('header.php')){
			include('header.php');
		}
	?>
</header>
<div class="content" role="main">
	<div class="wrapper">
		<div class="row">
			<aside class="sm-col-span-5 lg-col-span-5">
				<?php 
					if (file_exists('writersAside.php')){
						include('writersAside.php');
					}
				?>
			</aside>
			<article id="article" class="sm-col-span-4 lg-col-span-2">
				<?php
				if($_SESSION['welcome']){//initial welcome check
					$username = $_SESSION['user_name'];
					if($_SESSION['new_user']){
						$response = 'Welcome to Up-lifter Hope you enjoy the site';
					}else{
						$response = 'Welcome back '.$username;
					}
					echo '<h2>'.$response.'</h2>';
					$_SESSION['welcome'] = false;
				}else{
					echo '<h2>Create new title</h2>';
				}
				?>
				<p></p>
				<div class="box" id="boxtest">
					<?php 
						if (file_exists('messageBox.php')){
							include('messageBox.php');
						}
					?>
				</div>
				<div id="article0">
				<?php
					if(isset($_GET['delete_msg'])){
						$chat_init->deleteMessage($_GET['id']);
					}
					elseif(isset($_GET['uplift_msg'])){
						$chat_init->upliftMessage($_GET['id']);
					}
					elseif(isset($_GET['comment_msg'])){
						$chat_init->getComment($_GET['id']);
					}
					
					if(isset($_GET['new_message'])){
						if($chat_init->middle_limit){
							$chat_init->middle_limit = $chat_init->middle_limit+3;
						}else{
							$chat_init->middle_limit =13;
						}
						echo $chat_init->getNewMessages();
					}else{
						echo $chat_init->getNewMessages();
					}
				?>
				</div>
				
			</article>

			<aside class="sm-col-span-4 lg-col-span-1">
				<?php 
					if (file_exists('topicsAside.php')){
						include('topicsAside.php');
					}
				?>
			</aside>
		</div>
	</div>
</div>
<div id="posts" class="box4">
	<?php
		echo '<form class="more_button" method="get" onsubmit="return process(\'More_messages\', \'more_bt\', \'null\', \''.$_SESSION['iduser'].'\');">';
	?>
			<input class="more_button" type="submit" id="more_bt" name="new_message" value="more" >
		</form>
</div>

<script src="chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>