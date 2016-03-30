<?php
setcookie('sign_out', 'true', time()+86400, '/');
$_SESSION['iduser'] = '';
header('Location: responsiveHTML5BlogLogIn.php');