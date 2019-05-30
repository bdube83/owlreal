<?php
session_start();
//require_once('check_login_header.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Owlreal</title>
	<link rel="icon" href="top_comment.png">
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="http://owlreal.com/responsive-grid.css">
	<?php
		require_once('chat_init.php');
		//require_once('bongs_error_handler.php');
		require_once('facebookcustomshare.php');
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
				<h2>Comments</h2>
				<p></p>
				<?php
					if(isset($_GET['delete_msg']) && !isset($_SESSION['email'])){
						header('Location: responsiveHTML5BlogLogIn.php');
					}elseif(isset($_GET['delete_msg'])){
						$chat_init->deleteMessage($_GET['id']);
					}
					
					elseif(isset($_GET['uplift_msg']) && !isset($_SESSION['email'])){
						header('Location: responsiveHTML5BlogLogIn.php');
					}
					elseif(isset($_GET['uplift_msg'])){
						$chat_init->upliftMessage($_GET['id']);
					}
					
					elseif(isset($_GET['new_comment']) && !isset($_SESSION['email'])){
						header('Location: responsiveHTML5BlogLogIn.php');
					}
					elseif(isset($_GET['new_comment'])){
						$chat_init->addComment($_GET['get_text'], $_SESSION['iduser'], $_GET['id']);
					}
					echo $chat_init->getComment($_GET['id']);

				?>
			
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
<script src="http://owlreal.com/chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>