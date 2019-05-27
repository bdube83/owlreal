<?php
require_once('chat.class.php');
$mode = $_GET['mode'];
$id = 0;
$chat = new Chat();
$chat->current_user_id = $_GET['current_user_id'];
if($mode=='SendAndRetrieveNew'){
	$title = $_GET['title'];
	$message = $_GET['message'];
	$topic = $_GET['topic'];
	$user_id = $_GET['user_id'];

	//$id = $_GET['id'];
	
	if($title != ''|| $message != '' || $topic != ''){
		$chat->postNewMessage(htmlentities($title), htmlentities($message), htmlentities($topic), htmlentities($user_id));
	}
}elseif($mode=='DeleteAndRetrieveNew'){
	$chat->deleteAllMessage();
}elseif($mode=='RetrieveNew'){
	//$id = $_GET['id'];
}elseif($mode=='Comment'){
		$chat->comment($_GET['msg_id']);
}elseif($mode=='Uplift'){
		$chat->upliftMessage($_GET['msg_id']);
}elseif($mode=='UpliftComment'){
		$chat->upliftMessageC($_GET['msg_id']);
}elseif($mode=='DeleteOne'){
		$chat->deleteMessage($_GET['msg_id']);
}elseif($mode=='DeleteOneComment'){
		$chat->deleteMessageC($_GET['msg_id']);
}elseif($mode=='More_messages'){
	$chat->middle_limit = $chat->middle_limit+3;
}elseif($mode=='MoreMy_messages'){
		if($chat->middle_limit){
			$chat->middle_limit = $chat->middle_limit+3;

		}else{
			$chat->middle_limit = 13;
		}
}




if(ob_get_length()) ob_clean();

//Headers are sent to prevent browsers from caching
header('Cache-Control: no-cache, must revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml');
if($mode=='UpliftComment'){
	echo $chat->getComment($_GET['msg_id']);
}elseif($mode=='MoreMy_messages'){
	echo $chat->getMyMessages($_GET['current_user_id']);	
}else{
	echo $chat->getNewMessages($id);	
}
?>