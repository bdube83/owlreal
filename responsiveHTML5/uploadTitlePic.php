<?php
	require_once('upload.php');
	require_once('chat_init.php');
	$chat_init = new Chat_init();


	$upload = new Upload();
	$id='-1';
	if(empty($_POST['title'])){
		echo 'Your message was not sent. Please type a title.';
	}elseif(empty($_POST['imgurl'])){
		echo 'Your message was not sent. Please insert image.';
	}
	else{
		$id = $chat_init->postNewMessage($_POST['title'], $_POST['message'], $_POST['privatePost'], $_POST["writer_id_img"]);
	}

	$upload->upload_picV2("img", $id, $_POST["writer_id_img"], $_POST['imgurl'], $_POST["base64data"]);
	
    echo '{"response": "'.$_POST['privatePost'].'"}';


?>