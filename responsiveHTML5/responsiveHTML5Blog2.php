<!DOCTYPE html>
<html>
<head>
	<title>Responsive Grid</title>
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" type="text/css" href="responsive-grid.css">
	<?php
		require_once('chat_init.php');
	?>

</head>
<body  onload="responsiveHTML5Blog.php">
<header>
<div class="wrapper">
	<div class="row">
		
			<div class="sm-col-span-2 lg-col-span-4">
				<h1>Up-lifter</h1>
			</div>
				<nav class="sm-col-span-2 lg-col-span-4">
					<ul>
					<li><div id='post'>
							<a href="#">Latest posts</a>
						</div>
					</li>
					<li><a href="#">Popular posts</a></li>
					</ul>
				</nav>
	</div>
</div>
</header>
<div class="content" role="main">
	<div class="wrapper">
		<div class="row">
			<aside class="sm-col-span-4 lg-col-span-1">
				<h2></h2>
			</aside>
			<article id="article" class="sm-col-span-4 lg-col-span-2">
				<h2>Welcome</h2>
				<p></p>
				<div class="box" id="boxtest">
					<p>					
						<form method="post" onsubmit="return process('SendAndRetrieveNew');" href="responsiveHTML5Blog.php">
						<div class="row">
							<div class="topic">
							Topic: <br />
							<select  name='topic' id="topic">
							<div id="topic0">
							<?php
								echo getTopics();
							?>
							</div>
							</select>
							</div>
							
							<div class="sm_new_topic lg_new_topic">
							New topic: <br />
							<input type="text" name="new_topic" id="new_topic"/>
							</div>
						</div>
						<p />
						Title: <br />
						<input type="text" name="title" id="title" /> <p />
						Message: <br />
						
						<input type="text" name="message" id="message"/> <p />
						<input type="submit" value="Send" id="send"/>
						</form>
						<input id="refresh" type="button" value="Refresh" >
						<form>
						<input id="delete" type="button" value="Delete" >
						</form>
					
					</p>
				</div>
				<?php
					//echo getNewMessages();
					if(isset($_GET['delete_msg'])){
						deleteMessage($_GET['delete_msg']);
					}
					
				?>
				<div id="article0">
				</div>
				
				<div id="article1">
				</div>
				
				
				<div id="article2">
				</div>				
				
				<div id="article3">
				</div>				
				
				<div id="article4">
				</div>				
				
				<div id="article5">
				</div>				
				
				<div id="article6">
				</div>
				
			</article>
			<article id="article" class="sm-col-span-4 lg-col-span-2">
			</article>
			<aside class="sm-col-span-4 lg-col-span-1">
				<h2>Related Articles</h2>
				<nav>
				<ul>
				<li><a href="#">Article item 1</a></li>
				<li><a href="#">Article item 2</a></li>
				<li><a href="#">Article item 3</a></li>
				</ul>
				</nav>
			</aside>
			<div id="posts" class="sm-col-span-4 lg-col-span-2">
					<form>
					<input id="more_bt" type="button" value="more" >
					</form>
			</div>
		</div>
	</div>
</div>
<script src="responsive-grid.js" type="text/javascript"  ></script>
<script src="chatcom.js" type="text/javascript"  ></script>

<noscript>
	<a href="#">No javaScript</a>
</noscript>
</body>
</html>