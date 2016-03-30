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
<header>
<?php
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
					
				</ul>
			</div>
		</li>
		<li>
			<a href="#">
				<div>
					Online Booklet
				</div>
			</a>
		</li>
	</ul>
</nav>
</header>
<div class='header_under'></div>

<p></p>

<div class="content" role="main">
	<div class="wrapper">
		<div class="row">
			<aside class="sm-col-span-5 lg-col-span-5">
		        <div class = "box3">
			    </div>

			</aside>
			<article id="article" class="sm-col-span-4 lg-col-span-2">
				<h2>Team Purple</h2>
				<h3><?php echo "Welcome ";?></h3>
				<p></p>

				<div id="article0">
				<?php
				$target_img_box = '<img value="" type="image" src="https://screengrap-bdube83.c9users.io/responsiveHTML5/imgvis/front1.png" style="padding:1px;width:98%;height:50%;">';
				$target_img_box2 = '<img value="" type="image" src="https://screengrap-bdube83.c9users.io/responsiveHTML5/imgvis/key1.png" style="padding:1px;width:98%;height:50%;">';
				$target_img_box3 = '<img value="" type="image" src="https://screengrap-bdube83.c9users.io/responsiveHTML5/imgvis/key2.png" style="padding:1px;width:98%;height:50%;">';
				
				$response =
        				'<div class="box">'.
        				//'<div class="box2">'.
        				'<div class="message">'.
        				'<div class="inner_title">Front Page</div>'.														
        				$target_img_box.//js1
        				'</div>'.
        				'</div>';
				$response2 =
        				'<div class="box">'.
        				//'<div class="box2">'.
        				'<div class="message">'.
        				'<div class="inner_title">Key</div>'.														
        				$target_img_box2.//js1
        				'</div>'.
        				'</div>';
        				
		        $response3 =
        				'<div class="box">'.
        				//'<div class="box2">'.
        				'<div class="message">'.
        				'<div class="inner_title">Key</div>'.														
        				$target_img_box3.//js1
        				'</div>'.
        				'</div>';
        		echo $response;
        		echo $response2;
        		echo $response3;
        		
				/////////////////////////////////////////
				?>
				</div>

				
			</article>
			<aside class="sm-col-span-4 lg-col-span-1">

			</aside>
		</div>
	</div>
</div>
<?php
	echo '<center>';
	echo '</center>';
?>
<script src="chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>