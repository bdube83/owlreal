<?php

set_error_handler('WarningErrorHandler', E_ALL);

function WarningErrorHandler($number, $text, $theFile, $theLine){
	$errorMessage = 'Error: '.$number. chr(10).
					'Message: '.$text. chr(10).
					'File: '.$theFile. chr(10).
					'Line: '.$theLine;
	//echo $errorMessage;
	//exit;
}

?>