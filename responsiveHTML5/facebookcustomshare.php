<?php	
require_once('config.php');
require_once('chat_init.php');

$id = $_GET['id'];
$chat_initfb = new Chat_init();
$chat_initfb->assignattr($_GET['id']);

?>
        <meta property="fb:app_id"          content="436161610502101" /> 
        <meta property="og:type"            content="article" /> 
        <meta property="og:url"             content="http://owlreal.com/comment.php?id=<?php echo $chat_initfb->msgid;?>/" /> 
        <meta property="og:title"           content="<?php echo $chat_initfb->usertitle;?>" /> 
        <meta property="og:image"           content="http://owlreal.com/img/<?php echo $chat_initfb->iduser.'_'.$chat_initfb->msgid;?>.gif" /> 
        <meta property="og:description"    content="View image in 3D Virtual Reality." />