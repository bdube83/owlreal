
<?php
if (isset($_POST['google_user'])) {
    session_start();
    session_destroy();
    require_once('./User.php');
    $user = new User();
    
    //trim() removes unneccessry spacess.
    //Optional: consider useing stripslashes() as well incase a users enters backslashes.
    $email = trim($_POST['email']);
    
    $user_name = trim($_POST['user_name']);
    

    // check input
    if (empty($email)) {
          $user->stringLog .= 'Please enter your user email. ';
          echo '{"report": "'.$user->stringLog .'"}';
          return;
    }
    elseif (empty($user_name)) {
          $user->stringLog .= 'Please enter your user name. ';
          echo '{"report": "'.$user->stringLog .'"}';
          return;
    }else{
        // attempt database connection
        // escape special characters in input
        $email = $user->mysqli->real_escape_string($email);
        $user_name = $user->mysqli->real_escape_string($user_name);
        // check if emails exists
        $user->login_user($email, '1');
        $new_user = false;
    }
    if(!$user->iduser){
        /*register user*/
        $user->register_user($user_name, "", "27", $email, '1');
        $user->login_user($email, '1');
        $new_user= true;

    }
    
    
    if($user->iduser) {
        session_start();
        session_destroy();
        session_start();
        
        $_SESSION['email'] = $email;
		$_SESSION['user_name'] = $user_name;
        $_SESSION['iduser'] = $user->iduser;
        $_SESSION['welcome'] = true;
        $_SESSION['new_user'] = $new_user;

        setcookie('iduser', $user->iduser, time()+86400, '/');
        echo '{"report": "true",'.
            '"iduser": "'.$user->iduser.'"}';
              
        //header('Location: index.php');

    }else{
        // close connection
        unset($user->mysqli);
        setcookie('report2', $user->stringLog, time()+86400, '/');
        echo '{"report": "false"}';
        echo '{"report": "'.$user->stringLog.'"}';
        //echo '{"report": "email='.$email.'pass='.$password.'"}';
        //header('Location: index.php');
    }
    
    
    
 } else{
    echo '{"report": "No email address"}';
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

