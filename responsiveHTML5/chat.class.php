<?php
require_once('config.php');
require_once('bongs_error_handler.php');

class Chat
{

	private $mysqli;
	public $middle_limit;
	private $i;
	public $current_user_id;
	//constructor open database connection
	function __construct(){
		if($this->middle_limit){
			$this->middle_limit = $chat_init->middle_limit+3;
		}else{
			$this->middle_limit =10;
		}
		$this->i=0;
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		if ($this->mysqli === false) {
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
	}
	
	//destructor closes database connection
	function __destruct(){
		$this->mysqli->close();
	}
	
	//Truncates (empties) the table containing all messages
	public function deleteAllMessages(){
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$query = 'TRUNCATE TABLE chat';
		$result = $this->mysqli->query($query);
		$this->mysqli->close();

	}
	

	
	public function postNewMessage($title, $message, $topic, $iduser){
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$title = $this->mysqli->real_escape_string($title);
		$message = $this->mysqli->real_escape_string($message);
		$iduser = $this->mysqli->real_escape_string($iduser);
		$topic = $this->mysqli->real_escape_string($topic);
		$query = 'INSERT INTO chat(posted_on, title, message, iduser, topic)'.
		' VALUES (
		NOW(),
		"'.$title.'",
		"'.$message.'",
		"'.$iduser.'",
		"'.$topic.'") ';
		
		if($result = $this->mysqli->query($query)){}
		$this->mysqli->close();

	}
	
	public function upliftMessage($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		$add = 0;
		if($no_user == 0){
			$add = 1;
			$query = 'INSERT INTO uplift(message_id, iduser)'.
			' VALUES (
			"'.$id.'",
			"'.$this->current_user_id.'") ';
			
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$add = 0;
				echo "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}	
		}else{
			$add = -1;
			$query = 'DELETE FROM uplift WHERE message_id = "'.$id.'"';
			if ($mysqli->query($query) === true) {}
		}
		$query1 = 'SELECT uplift FROM chat WHERE message_id = "'.$id.'" ';
		if($result = $mysqli->query($query1)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$uplift = $row['uplift']+$add;
				}
			}
		}
		$query = 'UPDATE chat SET uplift = "'.$uplift.'" WHERE message_id = "'.$id.'" ';
		if ($mysqli->query($query) === true) {
			echo $mysqli->affected_rows . '  updated.';
		} else {
			echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
		}
		$mysqli->close();
	}
	
	public function upliftMessageC($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		$add = 0;
		if($no_user == 0){
			$add = 1;
			$query = 'INSERT INTO uplift(comment_id, iduser)'.
			' VALUES (
			"'.$id.'",
			"'.$this->current_user_id.'") ';
			
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$add = 0;
				echo "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}	
		}else{
			$add = -1;
			$query = 'DELETE FROM uplift WHERE comment_id = "'.$id.'"';
			if ($mysqli->query($query) === true) {}
		}
		
		
		$query1 = 'SELECT uplift FROM comment WHERE comment_id = "'.$id.'" ';
		if($result = $mysqli->query($query1)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$uplift = $row['uplift']+$add;
				}
			}
		}
		$query = 'UPDATE comment SET uplift = "'.$uplift.'" WHERE comment_id = "'.$id.'" ';
		if ($mysqli->query($query) === true) {
			echo $mysqli->affected_rows . ' updated.';
		} else {
			echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
		}
		$mysqli->close();
	}
	
	public function reportMessage($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		$query0 = 'SELECT COUNT(report_id) AS no_user FROM (SELECT * FROM report WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		$add = 0;
		if($no_user == 0){
			$add = 1;
			$query = 'INSERT INTO report(message_id, iduser)'.
			' VALUES (
			"'.$id.'",
			"'.$this->current_user_id.'") ';
			
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$add = 0;
				echo "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}	
		}else{
			$add = -1;
			$query = 'DELETE FROM report WHERE message_id = "'.$id.'"';
			if ($mysqli->query($query) === true) {}
		}
		$mysqli->close();
	}
	
	public function reportMessageC($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		$query0 = 'SELECT COUNT(report_id) AS no_user FROM (SELECT * FROM report WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		$add = 0;
		if($no_user == 0){
			$add = 1;
			$query = 'INSERT INTO report(comment_id, iduser)'.
			' VALUES (
			"'.$id.'",
			"'.$this->current_user_id.'") ';
			
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$add = 0;
				echo "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}	
		}else{
			$add = -1;
			$query = 'DELETE FROM report WHERE comment_id = "'.$id.'"';
			if ($mysqli->query($query) === true) {}
		}
		$mysqli->close();
	}
	
	public function comment($msg_id){
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$this->mysqli->close();
	}
	
	public function deleteMessage($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		$this->deleteMessageC($id);
		$query = 'DELETE FROM chat WHERE message_id = "'.$id.'"';
		if ($mysqli->query($query) === true) {
			echo $mysqli->affected_rows . ' delete updated.';
		} else {
			echo "ERROR: Could not delete query: . " . $mysqli->error;
		}
		$mysqli->close();
	}
	
	public function deleteMessageC($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		$query = 'DELETE FROM comment WHERE message_id = "'.$id.'"';
		if ($mysqli->query($query) === true) {
			echo $mysqli->affected_rows . ' delete updated.';
		} else {
			echo "ERROR: Could not execute query: $sql. " . $mysqli->error;
		}
		$mysqli->close();
	}
	public function getComment($id){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		$id = $mysqli->real_escape_string($id);
		
		$query = 'SELECT comment_id, message_id, comment, posted_on, iduser, uplift '.
				  'FROM comment WHERE comment_id = "'.$id.'" ';// WHERE message_id =comment_id;
		//XML response
		$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$response .= '<response>';
		if($result = $mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$id = $row['comment_id'];
					$time = $row['posted_on'];
					$comment = $row['comment'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser);
					$uplift_strC = $this->uplift_strC($id);
					$report_strC = $this->report_strC($id);
					$response .=  $this->getResponse($id,
											null,
											null,
											$time,
											null,
											$uplift,
											$iduser,
											null,
											$name ,
											$delete_bt,
											null,
											null,
											$report_strC,
											$uplift_strC);
				}
			$result->close();
			}
		}
		$response .= '</response>';
		$mysqli->close();
		return trim($response);
	}
	
	public function getMessage($message_id){
		
		$message_id = $this->mysqli->real_escape_string($message_id);
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on
			AS posted_on FROM chat'.
			' WHERE message_id= "'.$message_id.'"'.
			' AND topic= "false"'.
			' ORDER BY message_id ASC ';//the way that it's printed in the js makes it reverse.
			
		$response = '';
		//XML response
		$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$response .= '<response>';
        if ($result = $this->mysqli->query($query)) {
            $row = $result->fetch_assoc();
			$id = $row['message_id'];
			$topic = $row['topic'];
			$usertitle = $row['title'];
			$time = $row['posted_on'];
			$message = $row['message'];
			$uplift = $row['uplift'];
			$iduser = $row['iduser'];		
			$uplift_str = $this->uplift_str($id);
			$report_str = $this->report_str($id);
			$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
			if ($stmt = $this->mysqli->prepare($query)) {
				$stmt->execute();
				$stmt->bind_result($no_comments);
				while ($stmt->fetch()) {
				}
				$stmt->close();
			}
			$name = $this->getName($iduser);
			$delete_bt = $this->get_delete_bt($iduser);
			$response .= $this->getResponse($id,
									$topic,
									$usertitle,
									$time,
									$message,
									$uplift,
									$iduser,
									$uplift_str,
									$name ,
									$delete_bt,
									$no_comments,
									$report_str);
		}
		$result->close();
		$response .= '</response>';
		return trim($response); //preg_replace('/\s+/', '', $response);//str_replace(' ','',$response);
	}
	
	
	public function getMyMessages($iduser){
		
		$iduser = $this->mysqli->real_escape_string($iduser);
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on
			AS posted_on FROM chat'.
			' WHERE iduser= "'.$iduser.'"'.
			' AND topic= "false"'.
			' ORDER BY message_id ASC ';//the way that it's printed in the js makes it reverse.
			
		$response = '';
		//XML response
		$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$response .= '<response>';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i == $this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];		
					$uplift_str = $this->uplift_str($id);
					$report_str = $this->report_str($id);
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser);

					$response .= $this->getResponse($id,
											$topic,
											$usertitle,
											$time,
											$message,
											$uplift,
											$iduser,
											$uplift_str,
											$name ,
											$delete_bt,
											$no_comments,
											$report_str);
				}
				$result->close();
			}
		}
		$response .= '</response>';
		return trim($response); //preg_replace('/\s+/', '', $response);//str_replace(' ','',$response);
	}
	
	public function getMyPrivateMessages($iduser){
		
		$iduser = $this->mysqli->real_escape_string($iduser);
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on
			AS posted_on FROM chat'.
			' WHERE iduser= "'.$iduser.'"'.
			' AND topic= "true"'.
			' ORDER BY message_id ASC ';//the way that it's printed in the js makes it reverse.
			
		$response = '';
		//XML response
		$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$response .= '<response>';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i == $this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];		
					$uplift_str = $this->uplift_str($id);
					$report_str = $this->report_str($id);
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser);

					$response .= $this->getResponse($id,
											$topic,
											$usertitle,
											$time,
											$message,
											$uplift,
											$iduser,
											$uplift_str,
											$name ,
											$delete_bt,
											$no_comments,
											$report_str);
				}
				$result->close();
			}
		}
		$response .= '</response>';
		return trim($response); //preg_replace('/\s+/', '', $response);//str_replace(' ','',$response);
	}
	
	//Get new messages
	public function getNewMessages($id=0){
		$id = $this->mysqli->real_escape_string($id);
		if($id>0){
			$query =
			'
			SELECT message_id, title, message, topic, iduser, uplift, DATE_FORMAT(posted_on, "$H:%i:%s")
			AS posted_on FROM chat WHERE message_id > '
			.$id.
			' AND WHERE topic= "'.false.'"
			ORDER BY message_id ASC ';
		}else{
			$query =
			'
			SELECT message_id, title, message, topic, posted_on, iduser, uplift
			FROM chat 
			WHERE topic= "'.false.'"
			ORDER BY message_id ASC';
		}		
		//XML response
		$response = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$response .= '<response>';
		$response .=  $this->isDatabaseCleared($id);
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i == $this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];
					$uplift_str = $this->uplift_str($id);
					$report_str = $this->report_str($id);					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser);
					$response .= $this->getResponse($id,
											$topic,
											$usertitle,
											$time,
											$message,
											$uplift,
											$iduser,
											$uplift_str,
											$name ,
											$delete_bt,
											$no_comments,
											$report_str);
				}
				$result->close();
			}
		}
		$response .= '</response>';
		return trim($response); //preg_replace('/\s+/', '', $response);//str_replace(' ','',$response);
	}
	
	
	private function isDatabaseCleared($id){
		if($id>0){
			$check_clear = 'SELECT count(*) old FROM chat WHERE message_id<=' .$id;
			$result = $this->mysqli->query($check_clear);
			$row = $result->fetch_array(MYSQLI_ASSOC);
			if($row['old']==0)
				return '<clear>true</clear>';
		}
		return '<clear>false</clear>';
	}
	
	private function uplift_str($id){
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		if($no_user == 0){
			return 'uplifts';
		}else{
			return'uplifts';
		}
	}
	
	private function uplift_strC($id){
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		if($no_user == 0){
			return 'uplifts';
		}else{
			return'uplifts';
		}
	}
	
	private function report_str($id){
		$query0 = 'SELECT COUNT(report_id) AS no_user FROM (SELECT * FROM report WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		if($no_user == 0){
			return 'Report';
		}else{
			return'un-report';
		}
	}
	
	private function report_strC($id){
		$query0 = 'SELECT COUNT(report_id) AS no_user FROM (SELECT * FROM report WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$this->current_user_id.'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		if($no_user == 0){
			return 'Report';
		}else{
			return'un-report';
		}
	}
	
	private function getName($iduser){
		$query = 'SELECT name FROM users WHERE iduser= "'.$iduser.'"';
		if ($stmt = $this->mysqli->prepare($query)) {
			$stmt->execute();
			$stmt->bind_result($name);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		return $name;
	}
	
	private function get_delete_bt($iduser){
		if($iduser == $this->current_user_id){
			return "DELETE";
		}else{
			return "REPORT";
		}
	}
	
	private function getResponse($id =null,
					$topic = null,
					$usertitle = null,
					$time = null,
					$message = null,
					$uplift = null,
					$iduser = null,
					$uplift_str = null,
					$name = null,
					$delete_bt = null,
					$no_comments = null,
					$report_str = null,
					$report_strC = null,
					$uplift_strC = null){
						
		if(preg_match("#^https?://(?:www\.)?youtube.com#", $message)){
			$message = $this->youtube_convert($message);
		}		

		$url = 'img/'.$iduser.'_'.$id.'.gif';//js1
		$if_true = '<img value="" type="image" src="'.$url.'"  style="padding:1px;width:98%;height:50%;">';
		$if_false = ' ';
		$target_img_box = $this->checkFile($url, $if_true, $if_false);
		
		$url = 'propic/'.$iduser.'.gif';
		$if_true = $url;
		$if_false = 'propic/propic.gif';
		$propic = $this->checkFile($url, $if_true, $if_false);
		$response = '<id>' . $id . '</id>' .
			'<topic>' . $topic . '</topic>' .
			'<time>' . $time . '</time>' .
			'<title>' . htmlentities($usertitle) . '</title>' .
			'<iduser>' . $iduser . '</iduser>' .
			'<name>' . htmlentities($name) . '</name>' .
			'<delete_bt>' . $delete_bt . '</delete_bt>' .
			'<uplift>' . $uplift . '</uplift>' .
			'<report_str>' . $report_str . '</report_str>' .
			'<report_strC>' . $report_strC . '</report_strC>' .
			'<uplift_str>' . $uplift_str . '</uplift_str>' .
			'<uplift_strC>' . $uplift_strC . '</uplift_strC>' .
			'<no_comments>' . $no_comments . '</no_comments>' .
			'<message>' . htmlentities($message) . '</message>'.
			'<propic>' . $propic. '</propic>'.
			'<target_img_box>' . htmlentities($target_img_box). '</target_img_box>';
		return $response;
	}
	private function youtube_convert($url=""){
		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		$id =  $my_array_of_vars['v'];    
			// Output: C4kxS1ksqtw	
		$url_box =	'<object width="98%" height="350" data="http://www.youtube.com/v/'.$id.'"'.
					'type="application/x-shockwave-flash"><param name="src" value="http://www.youtube.com/v/'.$id.'" /></object>"';
		return $url_box;
	}
	
	public function checkFile($url, $if_true, $if_false){
		$target_img_box = '';
		if (file_exists($url)) {
			$target_img_box = $if_true;
		}
		else{
			$target_img_box = $if_false;
		}
		return $target_img_box;
	}
	
}
?>