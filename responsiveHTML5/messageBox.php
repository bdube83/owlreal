<?php
	require_once('upload.php');
	
	$upload_bt ='<div>'.
			'<br>'.
			'Click add image: '.
			'<br>'.
			'<input  type="file" name="fileToUpload" id="fileToUpload">'.
		'<div class="hide">'.
			'<input class="hide" name="writer_id_img" value="'.$_SESSION['iduser'].'"/>'.//When there is no js.
		'</div>'.
		'</div>'.
		'<br>';	

?>
<p>					
	<input  maxlength="50" type="text" name="title" id="title" placeholder="Title"/> <p />
		<!--
	<input type="text" name="message" id="message" style="padding: 20px 20px;"/>--> 
	<!--<div class="sm_new_topic1 lg_new_topic1">
	<textarea name="message" id="message" rows="5" cols="4" style="resize: none;position: relative;" placeholder="Enter your message here... or type a youtube link"></textarea>
	</div>-->
	<!--<p class="_button2" type="" width="40px"  onclick="">Press Ctrl+V </p>-->
	<!--<button class="_button" type="button"  onclick="pasteGrab()">Press Ctrl+V </button>-->

	<div class="hide">
		<input class="hide" id='writer_id_img' name="writer_id_img" value="<?php echo $_SESSION['iduser'];?>"/>//When there is no js.
		<div id='imgurl'> 
		</div>
	</div>
	
	<ul  id="log-box">
		<form enctype="multipart/form-data" action="/upload/image" method="post">
			  <label style="width:170px;padding-right: 26px;padding-left: 26px;color: rgba(255,255,255,.9);background: #4267b2;" for="image-file" class="_button">Select Image</label>
    		<input id="image-file" type="file" onchange="readURL(this);" style="visibility:hidden;display:flex;"  value="Click to upload image" />
		</ul>
		</form>

	<br>
	<br>
	<?php
		//echo $upload_bt;
	?>
	<button style="width:170px"  class="_button" type="" name="send" onclick="uploadImage()" value="Share Post" id="send">Share Post</button>
	<button style="width:170px"  class="_button" type="" name="send" onclick="uploadImageP()" value="Private Post" id="send"/>Private Post</button>

	

	
	<?php
	/*<input id="refresh" type="button" value="Refresh" >
	<form>
	<input id="delete" type="button" value="Delete" >
	</form>*/
	?>

</p>

<?php
// if(isset($_POST['send'])){
// 	$upload = new Upload();
// 	$id='-1';
// 	if(empty($_POST['title'])){
// 		echo 'Your message was not sent. Please type a title';
// 	}elseif(empty($_POST['message'])){
// 		//$upload->upload_pic("img", $id);
// 		//$upload->upload_picV2("img", $id);
// 		//echo 'Youxczxczx xczxce';

// 		$id = $chat_init->postNewMessage($_POST['title'], "", $_POST['new_topic'], $_SESSION['iduser']);
// 	}
// 	elseif($_POST['new_topic']!=''){
// 		$id = $chat_init->postNewMessage($_POST['title'], $_POST['message'], $_POST['new_topic'], $_SESSION['iduser']);
// 	}else{
// 		$id = $chat_init->postNewMessage($_POST['title'], $_POST['message'], $_POST['topic'], $_SESSION['iduser']);
// 	}
// 	//$upload = new Upload();
// 	//$upload->upload_pic("img", $id);
// 	//$upload->upload_picV2("img", $id);
	
// }
?>