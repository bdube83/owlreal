<?php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none'", false);

$chat_init = new Chat_init();
?>

<link rel="stylesheet" type="text/css" href="https://screengrap-bdube83.c9users.io/responsiveHTML5/font-awesome-4.3.0/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="https://screengrap-bdube83.c9users.io/responsiveHTML5/header.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<style>
.dropbtn {
    color: white;
    font-size: inherit;
    border: none;
    cursor: pointer;
    background-color:inherit;
    
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}
</style>
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
<div class="g-signin2" id=googleTop>
      
    <div class="dropdown" data-width="200" data-height="30" >
  		<button class="dropbtn">
  			<?php
				echo $_SESSION['user_name'];
			?>
		</button>
  		<div class="dropdown-content">
   			<a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/logoff.php">Sign-out</a>
    	</div>
</div>
</div>




