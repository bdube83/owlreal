<?php
require_once('check_login_header.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Responsive Grid</title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="responsive-grid.css">
	<?php
		require_once('chat_init.php');
		require_once('upload.php');
		//require_once('bongs_error_handler.php');
	?>

</head>
<body  >
	<?php
//require_once('facebookshare.php');
?>
<header>
	<?php
		if (file_exists('header.php')){
			include('header.php');
		}
	?>
</header>
<div class='header_under'></div>

<p></p>

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
				<h2 style="text-align:center">My Titles</h2>
				<h3><?php echo "Signed in as ".$_SESSION['user_name'];?></h3>
				<p></p>
				<div class="box" id="boxtest">
					<?php
						$init = $chat_init->upload_bt('index.php');
						$target_file = 'propic/'.$_SESSION['iduser'].'.gif';		
						$if_true = 	'<input value="" type="image" src="'.$target_file.'"  style="width:50px;height:40px;float:left">';
						$if_false = '<input value="" type="image" src="'.'propic/propic.gif'.'"  style="width:50px;height:40px;float:left">';
						$propic = $chat_init->checkFile($target_file, $if_true, $if_false);

						if (file_exists('messageBox.php')){
							include('messageBox.php');
							echo '<div class="box" id="boxtest">';
							echo $propic;
							echo $init;
							echo '</div>';
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
					elseif(isset($_GET['uplift_msgC'])){
						$chat_init->upliftMessageC($_GET['id']);
					}elseif(isset($_GET['comment_msg'])){
						$chat_init->getComment($_GET['id']);
					}

					if(isset($_GET['new_message'])){
						if($chat_init->middle_limit){
							$chat_init->middle_limit = $chat_init->middle_limit+3;
						}else{
							$chat_init->middle_limit =13;
						}
						echo $chat_init->getMyMessages($_SESSION['iduser']);
					}else{
						echo $chat_init->getMyMessages($_SESSION['iduser']);
					}
					if(isset($_POST['upload'])){
						$upload = new Upload();
						$upload->upload_pic();
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
<?php
	echo '<center>';
	require_once('footer.php');
	echo '</center>';
?>
<script src="chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>