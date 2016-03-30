<?php
require_once('Connection.php');

/**
 * Description of User
 *
 * @author uwcTransProjectTeam
 */
class User extends Connection {
    
    public $iduser;
    public $name;
    public $user_lname;
    public $user_cell;
    public $email;
    private $password;

    public function __construct() {
        parent::__construct();
    }   
    
    public function __destruct() {
    }
    
    public function assignUserAttributes($iduser){
        $query = 'SELECT *  '
                . ' FROM users WHERE iduser= "'.$iduser.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->iduser = $row['iduser'];
            $this->name = ucwords($row['name']); 
            $this->user_lname =  ucwords($row['user_lname']); 
            $this->user_cell =  $row['user_cell']; 
            $this->email =  $row['email']; 
            $this->password =  $row['password']; 

        }
        else {
             $this->stringLog .= "Oops!: Could not execute query: sql. " .$this->mysqli->error;
        }
    
    }
    public function assignUserEmailAttributes($email){
        $query = 'SELECT *'
                . ' FROM users WHERE email= "'.$email.'"';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
            $this->iduser = $row['iduser'];
            $this->name = $row['name']; 
            $this->user_lname =  $row['user_lname']; 
            $this->user_cell =  $row['user_cell']; 
            $this->email =  $row['email']; 
            $this->password =  $row['password'];
        }
        else {
             $this->stringLog .= "Oops!: Could not execute query: sql. " .$this->mysqli->error;
        }
        
    
    }
    
    public function displayUser($iduser) {
        //code
    }
    
    public function displayAllUsers() {
        //code
    }
    
    public function register_user($name, $user_lname, $user_cell, $email, $password){
        $name = htmlentities($this->mysqli->real_escape_string($name));
        $user_lname = htmlentities($this->mysqli->real_escape_string($user_lname));
        $user_cell = htmlentities($this->mysqli->real_escape_string($user_cell));
        $email = htmlentities($this->mysqli->real_escape_string($email));
        $password = htmlentities($this->mysqli->real_escape_string($password));
        $query = 'SELECT * '
                . ' FROM users WHERE email= "'.$email.'"';
        if ($result = $this->mysqli->query($query)) {
            if ($result->num_rows > 0) {
                $this->stringLog .= "The email ".$email." already exists.";
                return;
            }
        }
        $query = 'INSERT INTO users(name, user_lname, user_cell, email, password)'.
        ' VALUES (
        "'.$name.'",
        "'.$user_lname.'",
        "'.$user_cell.'",
        "'.$email.'",
        "'.$password.'") ';
        if ($this->mysqli->query($query) === true) {
            $this->assignUserEmailAttributes($email);
            $this->stringLog .= "".$this->mysqli->affected_rows . " user updated.";
            //login Go to Home page.
        } else {
            //user exist
            $this->stringLog .= "Oops!: Could not execute query: sql. " . $this->mysqli->error;
        }
    }
    
    public function login_user($email, $password) {
        $email = htmlentities($this->mysqli->real_escape_string($email));
        $password = htmlentities($this->mysqli->real_escape_string($password));
        
        $query = 'SELECT * '
                . ' FROM users WHERE email= "'.$email.'"';
        if ($result = $this->mysqli->query($query)) {
            
            $row = $result->fetch_assoc();
            $this->email =  $row['email']; 
            $this->password =  $row['password']; 
            if($this->email){
                if($this->password == $password){
                    //login Go to home page.
                    $this->assignUserEmailAttributes($email);
                    $this->stringLog .= "".$this->name;
                }else{
                    $this->stringLog .= "Wrong password.";
                }
            }else{
                $this->stringLog .= "The email ".$email." does not exists.";
            }

        }
        else {
            //somthing went wrong.
           $this->stringLog .= "Oops!: Could not execute query: sql. " .$this->mysqli->error;
        }
    }
    
}

?>