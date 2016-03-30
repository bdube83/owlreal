//Headers are sent to prevent browsers from caching
header('Cache-Control: no-cache, must revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml');


//XML response
$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
$response .= '<response>';
				$response .= '<id>1</id>' .
							'<color>#00000</color>' .
							'<time>1212</time>' .
							'<name>Victor</name>' .
							'<message>Hey yoy</message>';
$response .= '</response>';
echo $response;


<?php
//format for comments
$comment .= '<h2>Title: '.$usertitle.'</h2>'.
		'<p>'. $time.'</p>'.
		'<div class="box">'.
		$message.
		'<p />'.
		$uplift.'<input type="submit" value="Uplift" id="uplift"/>'.
		$comment.'<input type="submit" value="Comments" id="comments"/>'.
		'<input type="submit" value="Delete" id="'.$id.'"/>';

?>



<?php
require_once('chat.class.php');
$mode = "";
$mode = $_POST['mode'];
$id = 0;
$chat = new Chat();

if($mode=='SendAndRetrieveNew'){
	$name = $_POST['name'];
	$message = $_POST['message'];
	$color = $_POST['color'];
	$id = $_POST['id'];
	
	if($name != ''|| $message != '' || $color != ''){
		$chat->postNewMessage($name, $message, $color);
	}
}elseif($mode=='DeleteAndRetrieveNew'){
	$chat->deleteAllMessage();
}elseif($mode=='RetrieveNew'){
	$id = $_POST['id'];
}

if(ob_get_length()) ob_clean();

//Headers are sent to prevent browsers from caching
header('Cache-Control: no-cache, must revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml');

echo $chat->getNewMessages(id);
