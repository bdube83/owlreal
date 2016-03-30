<?php
require_once('config.php');
require_once('bongs_error_handler.php');

class Chat_init
{
	private $id;
	private $i;
	private $side_limit;
	public $middle_limit;
	private $mysqli;	
	//constructor open database connection
	function __construct(){
		if($this->middle_limit){
			$this->middle_limit = $chat_init->middle_limit+3;
		}else{
			$this->middle_limit =13;
		}
		$this->id=0;
		$this->i=0;
		$this->side_limit = 3;
		$this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
		if ($this->mysqli === false) {
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
	}
	
	//destructor closes database connection
	function __destruct(){
		$this->mysqli->close();
	}


	public function getMessageCount($count_type){
		$query = 'SELECT  count(message_id) AS count '.
				'FROM chat ';
		if($result = $this->mysqli->query($query)){
				if($result->num_rows > 0){
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						$count = $row['count'];
					}
					$result->close();
				}
			}
			
			return $count;
	}
	
	public function getTopics(){
			$query = 'SELECT DISTINCT `topic` FROM `chat`';
			$topic_ls = '';
			if($result = $this->mysqli->query($query)){
				if($result->num_rows > 0){
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						$topic = $row['topic'];
						$topic_ls .= '<option value="'.$topic.'">'.$topic.'</option>';
					}
					$result->close();
				}
			}
			
			return $topic_ls;
	}

	public function getTopTopics($topic){
		
		$topic = $this->mysqli->real_escape_string($topic);
		$query ='SELECT message_id, title, message, topic, iduser, uplift, posted_on'.
				' FROM chat'.
				' WHERE topic= "'.$topic.'"'.
				' ORDER BY uplift DESC ';
			
		$response = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i==$this->middle_limit){$this->i=0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];	
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()){
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false);
				}
				$result->close();
			}
		}
		$this->i=0;
		return trim($response);
	}

	public function getTopTopicsLinksHelper($topic){
		
		$topic = $this->mysqli->real_escape_string($topic);
		$query ='
			SELECT message FROM chat'.
			' WHERE topic= "'.$topic.'"'.
			' ORDER BY uplift DESC ';
		$message = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$message = $row['message'];
					break;
				}
				$result->close();
			}
		}
		
		return trim($message);
	}
	
	public function getTopTopicsLinks(){		
			$query = 'SELECT topic, count(topic) '.
					'FROM chat '.
					'GROUP BY topic '.
					'ORDER BY count(topic) DESC';
			$topic_ls = '';
			if($result = $this->mysqli->query($query)){
				if($result->num_rows > 0){
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						if($this->i==$this->side_limit){$this->i=0;break;}
						$this->i++;
						$topic = $row['topic'];
						$count = $row['count(topic)'];
						$message = $this->getTopTopicsLinksHelper($topic);
						$topic_ls .= '<div class = "box3">'.
									'<form method="get" action="responsiveHTML5TopTopics.php">'.
									'<li class="link_button">'.
									'<input class="link_button"  type="submit" name="top_topics" value="'.$topic.' has '.$count.' posts" >'.//change if one post e.g love has 1 posts							
									'</li>'.
									substr($message,0,30).'... click to read more'.
									'<div class="hide">'.
										'<input class="hide" name="top_topics_id" value="'.$topic.'"/>'.//When there is no js.
									'</div>'.
									'</form>'.
									'</div>';
					}
					$result->close();
				}
			}
			$this->i=0;
			return $topic_ls;
	}

	public function getTopWriters($iduser){
		$iduser = $this->mysqli->real_escape_string($iduser);
		$query ='SELECT message_id, title, message, topic, iduser, uplift, posted_on'.
				' FROM chat'.
				' WHERE iduser= "'.$iduser.'"'.
				' ORDER BY uplift DESC ';
			
		$response = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i==$this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];	
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()){
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false);
				}
				$result->close();
			}
		}
		$this->i=0;		
		return trim($response);
	}
	
	public function getTopWritersLinks(){
					
			$query = 'SELECT iduser, count(iduser) '.
					'FROM chat '.
					'GROUP BY iduser '.
					'ORDER BY count(iduser) DESC';
			$user_ls = '';
			if($result = $this->mysqli->query($query)){
				if($result->num_rows > 0){
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						if($this->i==$this->side_limit){$this->i =0;break;}
						$this->i++;
						$iduser = $row['iduser'];
						$count = $row['count(iduser)'];
						$sql = 'SELECT name, iduser FROM users WHERE iduser = "'.$iduser.'"';
						if ($result2 = $this->mysqli->query($sql)) {
							if($result2->num_rows > 0){
								while($row = $result2->fetch_array(MYSQLI_ASSOC)){
									// if yes, fetch the user name
									$user_name = $row['name'];
								}
							}
						}
						//$message = $this->getTopTopicsLinksHelper($topic);
						$user_ls .= '<div class = "box3">'.
									'<form method="get" action="responsiveHTML5TopWriters.php">'.
									'<li class="link_button">'.
									'<input class="link_button"  type="submit" name="top_writers" value="'.$user_name.' has '.$count.' posts" >'.//change if one post e.g love has 1 posts							
									'</li>'.
									//substr($message,0,30).'... click to read more'.
									'<div class="hide">'.
										'<input class="hide" name="top_writers_id" value="'.$iduser.'"/>'.//When there is no js.
									'</div>'.
									'</form>'.
									'</div>';
					}
					$result->close();
				}
			}
			$this->i=0;				
			return $user_ls;
	
	}


	public function deleteMessage($id){
			
			$id = $this->mysqli->real_escape_string($id);
			$query = 'DELETE FROM chat WHERE message_id = "'.$id.'"';
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				echo "ERROR: Could not execute query: $sql. " . $this->mysqli->error;
			}
			
	}

	public function upliftMessage($id){
			
			$id = $this->mysqli->real_escape_string($id);
			$query1 = 'SELECT uplift FROM chat WHERE message_id = "'.$id.'" ';
			if($result = $this->mysqli->query($query1)){
				if($result->num_rows > 0){
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						$uplift = $row['uplift']+1;
					}
				}
			}
			$query = 'UPDATE chat SET uplift = "'.$uplift.'" WHERE message_id = "'.$id.'" ';
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				echo "ERROR: Could not execute query: $sql. " . $this->mysqli->error;
			}	
	}

	public function postNewMessage($title, $message, $topic, $iduser){
			
			$title = htmlentities($this->mysqli->real_escape_string($title));
			$message = htmlentities($this->mysqli->real_escape_string($message));
			$iduser = htmlentities($this->mysqli->real_escape_string($iduser));
			$topic = htmlentities($this->mysqli->real_escape_string($topic));
			$query = 'INSERT INTO chat(posted_on, title, message, iduser, topic)'.
			' VALUES (
			NOW(),
			"'.$title.'",
			"'.$message.'",
			"'.$iduser.'",
			"'.$topic.'") ';
			
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				echo "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}			
	}

	public function addComment($comment, $iduser, $id){
			
			$comment = htmlentities($this->mysqli->real_escape_string($comment));
			$iduser = $this->mysqli->real_escape_string($iduser);
			$id = $this->mysqli->real_escape_string($id);
			$query = 'INSERT INTO comment(posted_on, comment, iduser, message_id)'.
			' VALUES (
			NOW(),
			"'.$comment.'",
			"'.$iduser.'",
			"'.$id.'") ';
			
			if ($this->mysqli->query($query) === true) {
				echo $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				echo "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}		

	}

	public function getCommentHelper($id){
		
		$id = $this->mysqli->real_escape_string($id);
		
		$query = 'SELECT comment_id, message_id, comment, posted_on, iduser, uplift '.
				  'FROM comment WHERE message_id = "'.$id.'" ';
				  
		$response = 'No comments';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				$response = "";
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i ==$this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['comment_id'];
					$time = $row['posted_on'];
					$comment = $row['comment'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser, $id, 'Comment');
					$uplift_strC = $this->uplift_strC($id);
					$response .= '<div id="'.$id.'box_comment'.'" >'.
							'<p>'. $time.'</p>'.
							'<div class="box">'.
						'<div>'.$name.'</div>'.
							$comment.
							'<p />'.
							'<form method="get" onsubmit="return process(\'UpliftComment\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.
							'<div id="'.$id.'upliftC">'.
							'<input class="_button" type="submit" name="uplift_msg"  value="'.$uplift_strC.'" id="uplift"/>'.
							'</div>'.
							'<div id="'.$id.'comment'.'">'.$uplift.' Amens</div>'.
							'<div class="hide">'.
								'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</div>'.
							'</form>'.
							'<p />'.
	
							$delete_bt.
							'</div>'.
							'</div>';
				}
			$result->close();
			}
		}
		$this->i=0;
		return trim($response);
	}

	public function getComment($id){
		
		$id = $this->mysqli->real_escape_string($id);
		
		$comment = $this->getCommentHelper($id);
		
		$query = 'SELECT message_id, title, message, topic, posted_on, iduser, uplift '.
				  'FROM chat WHERE message_id = "'.$id.'" ';
		$response = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];
					
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, false,$comment);
				}
			
				$result->close();
			}
		}
		
		return trim($response);
	}

	public function getTitleMessages($type){
		
		$type = $this->mysqli->real_escape_string($type);
		if($type == 'Top'){
			$type = 'uplift';
		}elseif($type == 'New'){
			$type = 'message_id';
		}
		
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on
			AS posted_on FROM chat'.
			//' WHERE iduser= "'.$iduser.'"'.
			' ORDER BY '.$type.' DESC ';
		$response = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i==$this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];		
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false);
				}
				$result->close();
			}
		}
		$this->i=0;		
		return trim($response);
	}

	public function getMyMessages($iduser){
		
		$iduser = $this->mysqli->real_escape_string($iduser);
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on
			AS posted_on FROM chat'.
			' WHERE iduser= "'.$iduser.'"'.
			' ORDER BY message_id DESC ';
			
		$response = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($this->i==$this->middle_limit){$this->i =0;break;}
					$this->i++;
					$id = $row['message_id'];
					$topic = $row['topic'];
					$usertitle = $row['title'];
					$time = $row['posted_on'];
					$message = $row['message'];
					$uplift = $row['uplift'];
					$iduser = $row['iduser'];		
					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false);
				}
				$result->close();
			}
		}
		$this->i=0;
		return trim($response);
	}

	public function getNewMessages($id=0){
		$query ='SELECT message_id, title, message, topic, posted_on, iduser, uplift
					FROM chat
					ORDER BY message_id DESC';
		$response = '';
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
					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false);
				}
				$result->close();
			}
		}
		$this->i=0;
		return trim($response);
	}

	public function messageBox($name="", $id="", $usertitle="", $time="", $message="", $uplift="", $no_comments=false,$comment=false){
		$iduser = $this->getUserId($id);
		$delete_bt = $this->get_delete_bt($iduser, $id, "");
		$uplift_str = $this->uplift_str($id);
		if($no_comments===false){
			$comment_btn = "";
			//echo '<h1>'.$no_comments.'</h1>';
		}else{
			//echo '<h1>'.$no_comments.'</h1>';
			$comment_btn = '<form method="get" action="responsiveHTML5BlogComment.php">'.
							'<input class="_button" type="submit" name="comment_msg"  value="Comment" id="comments"/>'.
							'<div>'.$no_comments.' Comments'.'</div>'.
							'<div class="hide">'.
								'<input  class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</div>'.
							'</form>';
		}
		if($comment===false){
			$comment = "";
			$comment_add = "";
		}else{
			$comment_add =	'<form method="get" >'.//js= return process. onsubmit="return process(\'AddComment\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');"
							'<div class="sm_new_topic lg_new_topic">'.
								'New comment: <br />'.
								'<input type="text" name="get_text" />'.
								'<input class="_button" type="submit" name="new_comment" value="Add" id="comment"/>'.
							'</div>'.
							'<div class="hide">'.
								'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</div>'.
							'</form>';
			}
		
		$response = '<div id="'.$id.'box'.'" >'.
				'<div class="title">Title: '.$usertitle.'</div>'.
				'<p>'. $time.'</p>'.
				'<div class="box">'.
				//'<div class="box2">'.
				'<div>'.$name.'</div>'.
				'<div class="message">'.
				$message.
				'</div>'.
				'<p />'.
				'<form method="get" onsubmit="return process(\'Uplift\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.
				'<div id="'.$id.'uplift">'.
				'<input class="_button" type="submit" name="uplift_msg"  value="'.$uplift_str.'" id=""/>'.
				'</div>'.
				'<div id="'.$id.'">'.$uplift.' Amens</div>'.
				'<div class="hide">'.
					'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
				'</div>'.
				'</form>'.
				'<p />'.
				$comment_btn.
				'<p />'.

				$delete_bt.
				
				$comment_add.
				$comment.
				'</div>'.//box end
				'</div>';
		return $response;
	}
	
	private function uplift_str($id){
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$_SESSION['iduser'].'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		if($no_user == 0){
			return 'Amen';
		}else{
			return'Un-Amen';
		}
	}
	
	private function uplift_strC($id){
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$_SESSION['iduser'].'" ';
		if ($stmt = $this->mysqli->prepare($query0)) {
			$stmt->execute();
			$stmt->bind_result($no_user);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		if($no_user == 0){
			return 'Amen';
		}else{
			return'Un-Amen';
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
		$name ='<form method="get" action="responsiveHTML5Writers.php">'.
			'<div class="name_button">'.
			'<input class="name_button"  type="submit" name="writer" value="'.$name.'" >'.//change if one post e.g love has 1 posts							
			'</div>'.
			//substr($message,0,30).'... click to read more'.
			'<div class="hide">'.
				'<input class="hide" name="writer_id" value="'.$iduser.'"/>'.//When there is no js.
			'</div>'.
			'</form>';
			return $name;
	}
	
	private function getUserId($id){
		$query = 'SELECT iduser FROM chat WHERE message_id= "'.$id.'"';
		if ($stmt = $this->mysqli->prepare($query)) {
			$stmt->execute();
			$stmt->bind_result($iduser);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		return $iduser;
	}
	
	private function get_delete_bt($iduser, $id, $meta){
		if($iduser == $_SESSION['iduser']){
			$delete_bt ='<form method="get" onsubmit="return process(\'DeleteOne'.$meta.'\', \''.$id.'\', \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.//js= return process.
						'<input class="_button" type="submit" name="delete_msg" value="Delete" id="delete"/>'.
						'<div class="hide">'.
							'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
						'</div>'.
						'</form>';
		}else{
			$delete_bt = '<form method="get" onsubmit="return process(\'ReportOne\', \''.$id.'\', \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.//js= return process.
						'<input class="red_button" type="submit" name="delete_msg" value="Report" id="delete"/>'.
						'<div class="hide">'.
							'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
						'</div>'.
						'</form>';
		}
		return $delete_bt;
	}
}
?>