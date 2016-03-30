<?php
echo "hey";
require_once('chat.class.php');
$mode = 'Uplift';
$msg_id = 144;

$id = 0;
$chat = new Chat();
$chat->current_user_id = 20;




//$chat->upliftMessage($msg_id);
echo $chat->getMessage($msg_id);
	//echo $chat->getNewMessages($id);	

?>