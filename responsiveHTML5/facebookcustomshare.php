<?php	
require_once('config.php');
require_once('chat_init.php');

$id = $_GET['id'];
$chat_initfb = new Chat_init();
$chat_initfb->assignattr($_GET['id']);

?>
        <meta property="fb:app_id"          content="1057669420920728" /> 
        <meta property="og:type"            content="article" /> 
        <meta property="og:url"             content="https://screengrap-bdube83.c9users.io/responsiveHTML5/<?php echo $chat_initfb->msgid;?>/" /> 
        <meta property="og:title"           content="<?php echo $chat_initfb->usertitle;?>" /> 
        <meta property="og:image"           content="https://screengrap-bdube83.c9users.io/responsiveHTML5/img/<?php echo $chat_initfb->iduser.'_'.$chat_initfb->msgid;?>.gif" /> 
        <meta property="og:description"    content="via ScreenGrap" />