<?php
require_once('config.php');
//require_once('bongs_error_handler.php');

class Chat_init
{
	private $id;
	private $i;
	private $side_limit;
	public $middle_limit;
	private $mysqli;	
	public $log;
	
	//user var
	public $msgid;
	public $topic;
	public $usertitle;
	public $time;
	public $message;
	public $uplift;
	public $iduser;
	public $picpath;
	
	//constructor open database connection
	function __construct(){
		if($this->middle_limit){
			$this->middle_limit = $chat_init->middle_limit+3;
		}else{
			$this->middle_limit =4;
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

	public function assignattr($id){
		$query = 'SELECT message_id, title, message, topic, posted_on, iduser, uplift, picpath '.
		  		'FROM chat WHERE message_id = "'.$id.'" ';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$this->msgid = $row['message_id'];
					$this->topic = $row['topic'];
					$this->usertitle = $row['title'];
					$this->time = $row['posted_on'];
					$this->message = $row['message'];
					$this->uplift = $row['uplift'];
					$this->iduser = $row['iduser'];
					$this->picpath = $row['picpath'];
					//$names_uplift = $chat_initfb->getUpliftNames($iduser, $id);
		
					//$name = $chat_initfb->getName($iduser);
				}
				$result->close();
			}
		}
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
					$z = 0;
					while($row = $result->fetch_array(MYSQLI_ASSOC)){
						if($z==10){break;}
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
		$query ='SELECT message_id, title, message, topic, iduser, uplift, posted_on, picpath'.
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
					$picpath = $row['picpath'];
					
					$names_uplift = $this->getUpliftNames($iduser, $id);
					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()){
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false, $picpath, false, $names_uplift);
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
					' WHERE topic= "false"'.
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
									'<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5TopTopics.php">'.
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
		$query ='SELECT message_id, title, message, topic, iduser, uplift, posted_on, picpath'.
				' FROM chat'.
				' WHERE iduser= "'.$iduser.'"'.
				' AND topic= "false"'.
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
					$picpath = $row['picpath'];
					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()){
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false, $picpath);
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
					' WHERE topic= "false"'.
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
									'<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5TopWriters.php">'.
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
				$this->log =  $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$this->log =  "ERROR: Could not execute query: $sql. " . $this->mysqli->error;
			}
			
	}

	public function postNewMessage($title, $message, $topic, $iduser){
			$id='-1';
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
			//$id = mysql_insert_id();
			if ($this->mysqli->query($query) === true) {
				$this->log = $this->mysqli->affected_rows . ' message updated.';
				if ($stmt = $this->mysqli->prepare('SELECT LAST_INSERT_ID()')) {//get id
						$stmt->execute();
						$stmt->bind_result($id);
						while ($stmt->fetch()) {
						}
						$stmt->close();
				}
			} else {
				$this->log = "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}
		return $id;
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
				$this->log =  $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$this->log =  "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}		

	}

	public function getCommentHelper($id){
		
		$id = $this->mysqli->real_escape_string($id);
		
		$query = 'SELECT comment_id, message_id, comment, posted_on, iduser, uplift, picpath '.
				  'FROM comment WHERE message_id = "'.$id.'" ';
				  
		$response = 'No comments';
		$uplift_str = $this->uplift_str($id);
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
					$picpath = $row['picpath'];
					$names_uplift = '';
					$names_uplift = $this->getUpliftNames($iduser, $id, true);
					if($names_uplift != ''){
						$names_uplift = '<form method="get" action="responsiveHTML5Writers.php">'.
										'<p style="float:left;font-size:13px;">Wowed by:</p>'.
										$names_uplift.
										'</form>';
					}
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser, $id, 'Comment');
					$uplift_strC = $this->uplift_strC($id);
					$response .= '<p />'.
							'<div id="'.$id.'box_comment'.'" >'.
							'<div class="box">'.
							'<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5Writers.php">'.
							'<div>'.
							 //'<input type="image" src="'.$picpath.'"  style="width:50px;height:40px;float:left">'.
							 $name.'</div>'.
							'<p>'. $time.'</p>'.
							'</form>'.
							$comment.
							'<p />'.
							'<form method="get" onsubmit="return process(\'UpliftComment\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.
							'<div id="'.$id.'upliftC">'.
							'<button name="uplift_msgC" id="'.$id.'upliftC'.'"  type="submit" class="_button">'.
								'<i class="fa fa-child fa-lg"></i>'.' '.
								$uplift.' '.$uplift_str.
							'</button>'.
							'</div>'.
							'<div id="'.$id.'comment'.'"></div>'.
							'<div class="hide">'.
								'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</div>'.
							'</form>'.
							'<p />'.
							$names_uplift;
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
	
		public function getTopCommentHelper($id){
		
		$id = $this->mysqli->real_escape_string($id);
		
		$query = 'SELECT MAX(uplift) as uplift, comment_id, message_id, comment, posted_on, iduser, picpath '.
				  'FROM comment WHERE message_id = "'.$id.'" LIMIT 1';
				  
		$response = 'No comments';
		$uplift_str = $this->uplift_str($id);
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
					$picpath = $row['picpath'];
					$name = $this->getName($iduser);
					$delete_bt = $this->get_delete_bt($iduser, $id, 'Comment');
					$uplift_strC = $this->uplift_strC($id);
					if($comment != ""){
						$response .= '<p />'.
								'<div id="'.$id.'box_comment'.'" >'.
									'<div class="box">'.
										'<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5Writers.php">'.
										'<div style="color:gray;font-size:12px;"><img src="top_comment.png" style="vertical-align:middle;display:inline-block;padding-bottom:3px;" height="15" width="15">Top comment</div>'.
										'</form>'.
										$name.
										'<h2>'.
										$comment.
										'<h2 />'.
										'<form method="get" onsubmit="return process(\'UpliftComment\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.
										'<div id="'.$id.'upliftC">'.
										'<button name="uplift_msgC" id="'.$id.'upliftC'.'"  type="submit" class="_button">'.
											'<i class="fa fa-child fa-lg"></i>'.' '.
											$uplift.' '.$uplift_str.
										'</button>'.
										'</div>'.
										'<div id="'.$id.'comment'.'"></div>'.
										'<div class="hide">'.
											'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
										'</div>'.
										'</form>'.
										'<p />'.
									'</div>'.

								'</div>';
					}
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
		
		$query = 'SELECT message_id, title, message, topic, posted_on, iduser, uplift, picpath '.
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
					$picpath = $row['picpath'];
					$names_uplift = $this->getUpliftNames($iduser, $id);

					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, false,$comment, $picpath, false, $names_uplift);
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
			SELECT message_id, title, message, topic, iduser, uplift, posted_on, picpath '.
			' FROM chat'.
			' WHERE topic= "false"'.
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
					$picpath = $row['picpath'];
					$names_uplift = $this->getUpliftNames($iduser, $id);

					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false, $picpath, false, $names_uplift);
				}
				$result->close();
			}
		}
		$this->i=0;		
		return trim($response);
	}

	private function getUpliftNames($iduser, $id,$comment=false){
		$z =0;
		if($comment){
			$query ='SELECT iduser FROM uplift WHERE comment_id="'.$id.'"';
		}else{
			$query ='SELECT iduser FROM uplift WHERE message_id="'.$id.'"';
		}
		$names_uplift = '';
		if($result = $this->mysqli->query($query)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					if($z == 10){break;}
					$z++;
					$iduser = $row['iduser'];
					$comma = '<p style="float:left;font-size:14px;height:45px;">, \'</p>';
					$names_uplift .= '<div>'.$this->getName($iduser,'style="float:left;font-size:13px;height:45px;"').$comma .'</div>';
				} 
			}
		}
		return $names_uplift;
	}
	
	public function getMyMessages($iduser){
		
		$iduser = $this->mysqli->real_escape_string($iduser);
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on, picpath'.
			' FROM chat'.
			' WHERE iduser= "'.$iduser.'"'.
			' AND topic= "false"'.
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
					$picpath = $row['picpath'];					
					$names_uplift = $this->getUpliftNames($iduser, $id);
					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false, $picpath, false, $names_uplift);
				}
				$result->close();
			}
		}
		$this->i=0;
		return trim($response);
	}

public function getMyPrivateMessages($iduser){
	$iduser = $this->mysqli->real_escape_string($iduser);
	$query ='
		SELECT message_id, title, message, topic, iduser, uplift, posted_on, picpath'.
		' FROM chat'.
		' WHERE iduser= "'.$iduser.'"'.
		' AND topic= "true"'.
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
				$picpath = $row['picpath'];					
				$names_uplift = $this->getUpliftNames($iduser, $id);
				
				$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
				if ($stmt = $this->mysqli->prepare($query)) {
					$stmt->execute();
					$stmt->bind_result($no_comments);
					while ($stmt->fetch()) {
					}
					$stmt->close();
				}
				$name = $this->getName($iduser);
				$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false, $picpath, false, $names_uplift, $topic);
			}
			$result->close();
		}
	}
	$this->i=0;
	return trim($response);
}
	public function getNewMessages($id=0){
		$query ='SELECT message_id, title, message, topic, posted_on, iduser, uplift, picpath
					FROM chat
					WHERE topic= "false"
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
					$picpath = $row['picpath'];
					$names_uplift = $this->getUpliftNames($iduser, $id);
					
					$top_comment = $this->getTopCommentHelper($id);

					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
						}
						$stmt->close();
					}
					$name = $this->getName($iduser);
					$response .= $this->messageBox($name, $id, $usertitle, $time, $message, $uplift, $no_comments,false, $picpath, false, $names_uplift, false, $top_comment);
				}
				$result->close();
			}
		}
		$this->i=0;
		return trim($response);
	}
	
	private function youtube_convert($url=""){
		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		$id =  $my_array_of_vars['v'];    
			// Output: C4kxS1ksqtw	
		$url_box =	'<object width="98%" height="350" data="http://www.youtube.com/v/'.$id.'"'.
					'type="application/x-shockwave-flash"><param name="src" value="http://www.youtube.com/v/'.$id.'" /></object>"';
		return $url_box;
	}
	
	public function messageBox($name="", $id="", $usertitle="", $time="", $message="", $uplift="", $no_comments=false,$comment=false, $picpath=false,  $wall=false, $names_uplift='', $topic='false', $top_comment=""){
		$iduser = $this->getUserId($id);
		$delete_bt = $this->get_delete_bt($iduser, $id);
		$uplift_str = $this->uplift_str($id);
		$upload_bt = '';
		$privatetab = '';
		if($topic == 'true') {
			$privatetab = "<div id='privatetab'>Private</div>";
		}
		
		if(preg_match("#^https?://(?:www\.)?youtube.com#", $message)){
			$message = $this->youtube_convert($message);
		}
		if($wall==true){
			$upload_bt = $this->upload_bt();
		 }
		if($no_comments===false){
			$comment_btn = "";
			//$this->log =  '<h1>'.$no_comments.'</h1>';
		}else{
			//$this->log =  '<h1>'.$no_comments.'</h1>';
			$comment_btn = '<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogComment.php">'.
			
							'<button type="submit" class="_button">'.
								'<i class="fa fa-comments fa-lg"></i>'.' '.
								$no_comments.' comments'.
							'</button>'.
							'<a class="hide">'.
								'<input  class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</a>'.
							'</form>';
		}
		if($comment===false){
			$comment = "";
			$comment_add = "";
		}else{
			$comment_add =	'<p />'.
							'<form method="get" >'.//js= return process. onsubmit="return process(\'AddComment\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');"
							'<div >'.
								'New comment: <br />'.
								'<input type="text" name="get_text" />'.
								'<input class="_button" type="submit" name="new_comment" value="Add" id="comment"/>'.
							'</div>'.
							'<div class="hide">'.
								'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</div>'.
							'</form>';
			}
		
		$target_file_msg = 'img/'.$iduser.'_'.$id.'.gif';//js1
		if (file_exists($target_file_msg)) {//js1
			$target_img_box = '<a href="https://screengrap-bdube83.c9users.io/responsiveHTML5/'.$id.'">
								<iframe style="border: none;width:  100%;height:  500px;" allow="geolocation; microphone; camera; midi; vr; encrypted-media" src="afame.php?imgURL='.$target_file_msg.'"></iframe>
								
					    		</iframe>
								</a>';
		}
		else{
			$target_img_box = '';
		}
		$target_file = 'propic/'.$iduser.'.gif';
		if (!file_exists($target_file)) {
			$target_file = 'propic/propic.gif';
		}

		if($names_uplift != ''){
			$names_uplift = '<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5Writers.php">'.
							'<p style="float:left;font-size:13px;">Wowed by:</p>'.
							$names_uplift.
							'</form>';
		}
		$response = '<div id="'.$id.'box'.'" >'.
				'<div class="box">'.
				//'<div class="box2">'.
				$upload_bt.
				'<form method="get" action="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5Writers.php">'.
				'<div>'.
				 '<input value="" type="image" src="https://screengrap-bdube83.c9users.io/responsiveHTML5/'.$target_file.'"  style="width:50px;height:40px;float:left">'.
                 $name.'</div>'.
				'</form>'.
				'<p>'. $time.'</p>'.
				$privatetab.
				'<div class="message">'.
				
				'<div class="inner_title">'.$top_comment.'</div>'.														
				$message.
				
				$target_img_box.//js1
				'<div class="inner_title">'.$usertitle.'</div>'.														

				'</div>'.
				'<p />'.
				'<div style="display:flex" >'.
				'<form method="get" onsubmit="return process(\'Uplift\', \''.$id.'\',  \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.
				'<div id="'.$id.'uplift">'.
				'<button name="uplift_msg" type="submit" class="_button">'.
								'<i class="fa fa-child fa-lg"></i>'.' '.
								$uplift.' '.$uplift_str.
				'</button>'.
				'</div>'.
				'<div id="'.$id.'"></div>'.
				'<div class="hide">'.
					'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
				'</div>'.
				'</form>'.

				$comment_btn.

				$delete_bt.
				'<div class="fb-share-button" 
					data-href="https://screengrap-bdube83.c9users.io/responsiveHTML5/'.$id.'/"
				data-layout="button_count">
				</div>'.
				'</div>'.
				
				$names_uplift.
				'</br>'.
				'</br>'.
				$comment_add.
				'</p>'.
				$comment.
				'</div>'.//box end
				'</div>';
		return $response;
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
			return 'Wow';
		}else{
			return'Wow';
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
			return 'Wow';
		}else{
			return'Wow';
		}
	}	
	
	private function report_str($id){
		$query0 = 'SELECT COUNT(report_id) AS no_user FROM (SELECT * FROM report WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$_SESSION['iduser'].'" ';
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
		$query0 = 'SELECT COUNT(report_id) AS no_user FROM (SELECT * FROM report WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$_SESSION['iduser'].'" ';
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
	private function getName($iduser, $style=''){
		$query = 'SELECT name FROM users WHERE iduser= "'.$iduser.'"';
		if ($stmt = $this->mysqli->prepare($query)) {
			$stmt->execute();
			$stmt->bind_result($name);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		$name =
			'<input class="name_button"  type="submit" name="writer" value="'.$name.'" '.$style.'>'.//change if one post e.g love has 1 posts							
			//substr($message,0,30).'... click to read more'.
			'<div class="hide">'.
				'<input class="hide" name="writer_id" value="'.$iduser.'">'.//When there is no js.
			'</div>';
			
			return $name;
	}
	
	public function upload_bt($url='responsiveHTML5BlogWall.php'){
		$upload_bt =
			'<form action="https://screengrap-bdube83.c9users.io/responsiveHTML5/'.$url.'" method="post" enctype="multipart/form-data">'.
				'Click to change your profile image: '.
				'<input  type="file" name="fileToUpload" id="fileToUpload">'.
				'</br>'.
				'<input style="float:left;" class="_button" type="submit" value="Change" name="upload">'.
			'<div class="hide">'.
				'<input class="hide" name="writer_id_img" value="'.$_SESSION['iduser'].'"/>'.//When there is no js.
			'</div>';
			'</form>';	
			return $upload_bt;
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
	
	private function get_delete_bt($iduser, $id, $meta=null){
		$report = "";
		$comment = '';
		if($meta==="Comment"){
			$report = $this->report_strC($id);
			$comment = 'C';
		}else{
			$report = $this->report_str($id);
		}
		if($iduser == $_SESSION['iduser']){
			$delete_bt ='<form method="get" onsubmit="return process(\'DeleteOne'.$meta.'\', \''.$id.'\', \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.//js= return process.						
						'<button type="submit" class="_button">'.
							'<i class="fa fa-trash-o  fa-lg"></i>'.' Delete'.
						'</button>'.
						'<div id="'.$id.'deleteP'.$comment.'"></div>'.
						'<div class="hide">'.
							'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
						'</div>'.
						'</form>';
		}else{
			$delete_bt = '<form method="get" onsubmit="return process(\'ReportOne'.$meta.'\', \''.$id.'\', \''.$iduser.'\', \''.$_SESSION['iduser'].'\');">'.//js= return process.
						'<div id="'.$id.'report'.$comment.'">'.
						'<button type="submit" class="_button">'.
							'<i class="fa fa-exclamation-triangle"></i> '.$report.
						'</button>'.
						'</div>'.
						'<div id="'.$id.'reportP'.$comment.'"></div>'.
						'<div class="hide">'.
							'<input class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
						'</div>'.
						'</form>';
		}
		return $delete_bt;
	}
	
	public function upliftMessageC($id){
		$id = $this->mysqli->real_escape_string($id);
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE comment_id = "'.$id.'" ) AS messages WHERE iduser = "'.$_SESSION['iduser'].'" ';
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
			"'.$_SESSION['iduser'].'") ';
			
			if ($this->mysqli->query($query) === true) {
				$this->log =  $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$add = 0;
				$this->log =  "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}	
		}else{
			$add = -1;
			$query = 'DELETE FROM uplift WHERE comment_id = "'.$id.'"';
			if ($this->mysqli->query($query) === true) {}
		}
		
		
		$query1 = 'SELECT uplift FROM comment WHERE comment_id = "'.$id.'" ';
		if($result = $this->mysqli->query($query1)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$uplift = $row['uplift']+$add;
				}
			}
		}
		$query = 'UPDATE comment SET uplift = "'.$uplift.'" WHERE comment_id = "'.$id.'" ';
		if ($this->mysqli->query($query) === true) {
			$this->log =  $this->mysqli->affected_rows . ' amen updated.';
		} else {
			$this->log =  "ERROR: Could not execute query: $sql. " . $this->mysqli->error;
		}
	}
	
	public function upliftMessage($id){
		$id = $this->mysqli->real_escape_string($id);
		$query0 = 'SELECT COUNT(uplift_id) AS no_user FROM (SELECT * FROM uplift WHERE message_id = "'.$id.'" ) AS messages WHERE iduser = "'.$_SESSION['iduser'].'" ';
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
			"'.$_SESSION['iduser'].'") ';
			
			if ($this->mysqli->query($query) === true) {
				$this->log =  $this->mysqli->affected_rows . ' row(s) updated.';
			} else {
				$add = 0;
				$this->log =  "ERROR: Could not execute query: sql. " . $this->mysqli->error;
			}	
		}else{
			$add = -1;
			$query = 'DELETE FROM uplift WHERE message_id = "'.$id.'"';
			if ($this->mysqli->query($query) === true) {}
		}
		$query1 = 'SELECT uplift FROM chat WHERE message_id = "'.$id.'" ';
		if($result = $this->mysqli->query($query1)){
			if($result->num_rows > 0){
				while($row = $result->fetch_array(MYSQLI_ASSOC)){
					$uplift = $row['uplift']+$add;
				}
			}
		}
		$query = 'UPDATE chat SET uplift = "'.$uplift.'" WHERE message_id = "'.$id.'" ';
		if ($this->mysqli->query($query) === true) {
			$this->log =  $this->mysqli->affected_rows . ' amen updated.';
		} else {
			$this->log =  "ERROR: Could not execute query: $sql. " . $this->mysqli->error;
		}
	}
}
?>