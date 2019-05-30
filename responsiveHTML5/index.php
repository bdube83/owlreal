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
	<title>Home</title>
	<link rel="icon" href="top_comment.png">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="responsive-grid.css">
	<script src="chatcom.js" type="text/javascript"  ></script>
	<script src="aframe.js" type="text/javascript"  ></script>
	<?php
		require_once('chat_init.php');
		require_once('chat.class.php');
		$chat = new Chat();
		//require_once('facebookcustomshare.php');
		//require_once('bongs_error_handler.php');
		//$chat->reportMessage(1);
	?>

</head>
<body  >
<?php
require_once('facebookshare.php');
?>


<header>
	<?php 
		if (file_exists('header.php')){
			include('header.php');
		}
	?>
</header>
<div class='header_under'></div>

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
				$init = $chat_init->upload_bt('index.php');
				$target_file = 'propic/'.$_SESSION['iduser'].'.gif';		
				$if_true = 	'<input value="" type="image" src="'.$target_file.'"  style="width:50px;height:40px;float:left">';
				$if_false = '<input value="" type="image" src="'.'propic/propic.gif'.'"  style="width:50px;height:40px;float:left">';
				$propic = $chat_init->checkFile($target_file, $if_true, $if_false);
				$note = '<p>';
				$img_response = '<p>';
				if(isset($_SESSION['welcome']) && $_SESSION['welcome'] == true){//initial welcome check
					$username = $_SESSION['user_name'];
					if(isset($_SESSION['new_user']) && $_SESSION['new_user'] == 'true'){
						$response = 'Welcome to owlreal';
						//$img_response .= 'First press:<br>  <img src="http://owlreal.com/website_images/tut1.gif" alt="Tut" height="70" width="70"></p> then ctr+v';
						$note = '<h3>App currntly only works well with Google-chrome.</h3>';
					}else{
						$response = 'Welcome back '.$username;
					}
					echo '<h2>'.$response.'</h2></p>';
					echo $note;
					
					
					echo $img_response;
					$_SESSION['welcome'] = false;
				}else{
					echo '<h2>Create new post</h2>';
				}
				?>
				<p></p>
				<div class="box" id="boxtest">
					<?php 
						if (file_exists('messageBox.php')){
							echo '<center>';
							include('messageBox.php');
							echo '</center>';
							
							/*echo '<div class="box" id="boxtest">';
							echo $propic;
							echo $init;
							echo '</div>';*/
						}
						if(isset($_POST['upload'])){
							$upload = new Upload();
							$upload->upload_pic();
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
<center>
<div id="posts" class="box4">
	<?php
		echo '<form method="get" onsubmit="return process(\'More_messages\', \'more_bt\', \'null\', \''.$_SESSION['iduser'].'\');">';
	?>
			<!--<input class="_button" type="submit" id="more_bt" name="new_message" value="more" >-->
		</form>
</div>
</center>

<script src="chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>