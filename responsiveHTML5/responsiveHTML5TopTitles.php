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
				<h2>Top Titles</h2>
				<p></p>

				<?php
					if(isset($_GET['delete_msg'])){
						$chat_init->deleteMessage($_GET['id']);
					}
					elseif(isset($_GET['uplift_msg'])){
						$chat_init->upliftMessage($_GET['id']);
					}elseif(isset($_GET['comment_msg'])){
						$chat_init->getComment($_GET['id']);
					}
					echo $chat_init->getTitleMessages('Top');
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
</div>
<script src="chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>