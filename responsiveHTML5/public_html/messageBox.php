<p>					
	<form method="post" >
	<div class="row">
		<div class="topic">
		Topic: <br />
		<select  name='topic' id="topic">
		<div id="topic0">
		<?php
			echo $chat_init->getTopics();
		?>
		</div>
		</select>
		</div>
		
		<div class="sm_new_topic lg_new_topic">
		 <br />
		<input type="text" name="new_topic" placeholder="New Topic"id="new_topic"/>
		</div>
	</div>
	<p />
	<br />
	<input type="text" name="title" id="title" placeholder="Title"/> <p />
		<!--
	<input type="text" name="message" id="message" style="padding: 20px 20px;"/>--> 
	<p />
	<br>
	<div class="sm_new_topic1 lg_new_topic1">
	<textarea name="message" id="message" rows="5" cols="4" style="resize: none;position: relative;" placeholder="Enter your message here..."></textarea>
	</div>
	<input class="more_button" type="submit" name="send" value="Send" id="send"/>
	</form>
	
	<?php
	/*<input id="refresh" type="button" value="Refresh" >
	<form>
	<input id="delete" type="button" value="Delete" >
	</form>*/
	?>

</p>

<?php
if(isset($_POST['send'])){
	if(empty($_POST['title'])){
		echo 'Your message was not sent. Please type a title';
	}elseif(empty($_POST['message'])){
		echo 'Your message was not sent. Please type a message';
	}
	elseif($_POST['new_topic']!=''){
		$chat_init->postNewMessage($_POST['title'], $_POST['message'], $_POST['new_topic'], $_SESSION['iduser']);
	}else{
		$chat_init->postNewMessage($_POST['title'], $_POST['message'], $_POST['topic'], $_SESSION['iduser']);
	}
}
?>