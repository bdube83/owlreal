<?php
require_once('config.php');
//require_once('bongs_error_handler.php');

class Chat_login
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
			$this->middle_limit =3;
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
	public function getDayMessages($message_id){		
		$message_id = $this->mysqli->real_escape_string($message_id);		
		$query ='
			SELECT message_id, title, message, topic, iduser, uplift, posted_on, picpath '.
			' FROM chat'.
			' WHERE message_id= "'.$message_id.'"'.
			' AND topic= "false"'.
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
						while ($stmt->fetch()) {
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

					
					$query = 'SELECT COUNT(comment_id) AS no_comments FROM comment WHERE message_id= "'.$id.'"';
					if ($stmt = $this->mysqli->prepare($query)) {
						$stmt->execute();
						$stmt->bind_result($no_comments);
						while ($stmt->fetch()) {
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
	
	private function getName($iduser){
		$query = 'SELECT name FROM users WHERE iduser= "'.$iduser.'"';
		if ($stmt = $this->mysqli->prepare($query)) {
			$stmt->execute();
			$stmt->bind_result($name);
			while ($stmt->fetch()) {
			}
			$stmt->close();
		}
		$name =
			'<div class="name_button">'.
			'<a style="text-decoration:none;" href="#" class="name_button" style="height:40px" >'.$name.'</a>'.//change if one post e.g love has 1 posts							
			'</div>';
			
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
	
	private function youtube_convert($url=""){
		parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
		$id =  $my_array_of_vars['v'];    
			// Output: C4kxS1ksqtw	
		$url_box =	'<object width="98%" height="350" data="http://www.youtube.com/v/'.$id.'"'.
					'type="application/x-shockwave-flash"><param name="src" value="http://www.youtube.com/v/'.$id.'" /></object>"';
		return $url_box;
	}
	
	
	public function messageBox($name="", $id="", $usertitle="", $time="", $message="", $uplift="", $no_comments=false,$comment=false, $picpath=false,  $wall=false){
		$iduser = $this->getUserId($id);
		$delete_bt = "";
		$uplift_str = "Wow";
		$upload_bt = '';
		if(preg_match("#^https?://(?:www\.)?youtube.com#", $message)){
			$message = $this->youtube_convert($message);
		}
		 if($wall==true){
			$upload_bt = $this->upload_bt();
		 }
		if($no_comments===false){
			$comment_btn = "";
			//echo '<h1>'.$no_comments.'</h1>';
		}else{
			//echo '<h1>'.$no_comments.'</h1>';
			$comment_btn = '<a href="#">'.
			
							'<button type="submit" class="_button">'.
								'<i class="fa fa-comments fa-lg"></i>'.' '.
								$no_comments.' comments'.
							'</button>'.
							'<a class="hide">'.
								'<input  class="hide" name="id" value="'.$id.'"/>'.//When there is no js.
							'</a>'.
							'</a>';
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
			$target_img_box = '<img value="" type="image" src="'.$target_file_msg.'"  style="width:98%;height:50%;">';
		}
		else{
			$target_img_box = '';
		}
		$target_file = 'propic/'.$iduser.'.gif';
		if (!file_exists($target_file)) {
			$target_file = 'propic/propic.gif';
		}
		$response = '<div id="'.$id.'box'.'" >'.
				'<div class="box">'.
				//'<div class="box2">'.
				$upload_bt.
				'<a href="#" >'.
				 '<img value="" type="image" src="'.$target_file.'"  style="width:50px;height:40px;float:left">'.
				 $name.
				'</a>'.
				'<p>'. $time.'</p>'.
				'<div class="message">'.
				'<div class="inner_title">'.$usertitle.'</div>'.														
				$message.
				$target_img_box.//js1
				'</div>'.
				'<p />'.
				'<div style="display:flex" >'.
				'<a href="#">'.
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
				'</a>'.
				$comment_btn.

				$delete_bt.
				'</div>'.
				
				$comment_add.
				'</p>'.
				$comment.
				'</div>'.//box end
				'</div>';
		return $response;
	}
}
?>