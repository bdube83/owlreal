var xmlHttp = createXmlHttpRequestObject();
var article = document.getElementById('article');
var mode_global = "";
var current_user_id_global="";
var msg_id_global;

function createXmlHttpRequestObject(){
	var xmlHttp;
	
	if(window.XMLHttpRequest){
		xmlHttp = new XMLHttpRequest();
	}else{
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	return xmlHttp;
}
	
function process(action, msg_id, user_id, current_user_id){
	if(action=="Uplift"){
		document.getElementById(msg_id).innerHTML = '+-1 Amens';
	}
	if(action=="UpliftComment"){
		document.getElementById(msg_id+'comment').innerHTML = '+-1 Amens';
	}
	if(xmlHttp){
		try{
			current_user_id_global = current_user_id;
			mode_global = action;
			msg_id_global = msg_id;
			if(mode_global =="SendAndRetrieveNew" ){//checks if user send a message
				var title = encodeURIComponent(document.getElementById('title').value);
				var message = encodeURIComponent(document.getElementById('message').value);
				var topic = encodeURIComponent(document.getElementById('new_topic').value);
				if(topic==null)topic = encodeURIComponent(document.getElementById('topic').value);
				var parameters = "title="+title+"&message="+message+"&mode="+action+"&topic="+topic+"&msg_id="+msg_id+"&user_id="+user_id+
				"&current_user_id="+current_user_id;
			}else{
				var parameters = "mode="+action+"&msg_id="+msg_id+"&user_id="+user_id+"&current_user_id="+current_user_id;
			}
			
			xmlHttp.open("GET","chat.php?"+parameters,true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlHttp.send(parameters);
			xmlHttp.onreadystatechange = setTimeout(handleServerResponse, 2000);
			xmlHttp.send(null);

		}catch(e){
		
			//alert(e.toString() );
		}
	}
	return false;
}

function handleServerResponse(){
	//boxtest = document.getElementById('boxtest');
	if(xmlHttp.readyState==4){
		//boxtest.innerHTML = "State 4: sever connection established";
		if(xmlHttp.status==200){
			//try{
				if(mode_global =="SendAndRetrieveNew" ||  mode_global =="More_messages" || mode_global =="MoreMy_messages"){
					handleResponse();
				}else{
					handleResponseUplift();//checks if user uplifts a message
				}
			//}catch(e){
				//alert(e.toString() );
			//}
			
		}else{
			//alert(xmlHttp.statusText);
		}
	}else{
		setTimeout('handleServerResponse()',1000);
	}
}

function handleResponseUplift(){
	if(xmlHttp.overrideMimeType){
		xmlHttp.overrideMimeType("text/xml");
		var xmlResponse = xmlHttp.responseXML;
		var root = xmlResponse.documentElement;
		var msg_ids= root.getElementsByTagName('id');
		var uplifts = root.getElementsByTagName('uplift');
		var uplift_strs = root.getElementsByTagName('uplift_str');
		if(mode_global=="UpliftComment"){
			var uplift_strsC = root.getElementsByTagName('uplift_strC');
		}
		for(var i=0;msg_ids.length>i;i++){
			var msg_id = msg_ids.item(i).firstChild.data;
			var uplift = uplifts.item(i).firstChild.data;
			if(mode_global=="Uplift"){
				var uplift_str = uplift_strs.item(i).firstChild.data;
			}
			if(mode_global=="UpliftComment"){
				var uplift_strC = uplift_strsC.item(i).firstChild.data;
			}
			
			if(mode_global=="Uplift" && msg_id_global==msg_id ){
				document.getElementById(msg_id_global).innerHTML = uplift+' Amens';
				document.getElementById(msg_id_global+"uplift").innerHTML =
				'<input class="_button" type="submit" name="uplift_msg"  value="'+uplift_str+'" id=""/>';
			}
			if(mode_global=="UpliftComment"){
				document.getElementById(msg_id+'comment').innerHTML = uplift+' Amens';
				document.getElementById(msg_id_global+"upliftC").innerHTML = 
				'<input class="_button" type="submit" name="uplift_msg"  value="'+uplift_strC+'" id="uplift"/>';
			}
		}
		if(mode_global=="DeleteOne"){
			document.getElementById(msg_id_global+'box').innerHTML = "";//removing only one box
		}
		if(mode_global=="DeleteOneComment"){
			document.getElementById(msg_id_global+'box_comment').innerHTML = "";//removing only one box
		}

	}
}
function handleResponse(){
	if(xmlHttp.overrideMimeType){
		xmlHttp.overrideMimeType("text/xml");
		var xmlResponse = xmlHttp.responseXML;
		var root = xmlResponse.documentElement;
		var titles = root.getElementsByTagName('title');
		var messages = root.getElementsByTagName('message');
		var times = root.getElementsByTagName('time');
		var uplifts = root.getElementsByTagName('uplift');
		var msg_ids= root.getElementsByTagName('id');
		var idusers = root.getElementsByTagName('iduser');
		var names = root.getElementsByTagName('name');
		var uplift_strs = root.getElementsByTagName('uplift_str');
		var delete_bts = root.getElementsByTagName('delete_bt');
		var no_comments = root.getElementsByTagName('no_comments');
		var response = "";
		//var stuff = "";
		for(var i=0;titles.length>i;i++){
			var title = titles.item(i).firstChild.data;
			var message = messages.item(i).firstChild.data;
		    var time = times.item(i).firstChild.data;
		    var uplift = uplifts.item(i).firstChild.data;
			var msg_id = msg_ids.item(i).firstChild.data;
			var iduser = idusers.item(i).firstChild.data;
			var name = names.item(i).firstChild.data;
			var uplift_str = uplift_strs.item(i).firstChild.data;
			var delete_bt_test = delete_bts.item(i).firstChild.data;
			var no_comment = no_comments.item(i).firstChild.data;
			response = messageBox(uplift_str, delete_bt_test, name, iduser, msg_id, title, time, message, uplift, no_comment,false) + response;
			if(mode_global=="Uplift"){
				document.getElementById(msg_id).innerHTML = uplift+' Amens';
			}
		}
		var article = document.getElementById('article0');
		article.innerHTML = response;
		
		if(mode_global=="DeleteOne"){
			document.getElementById(msg_id_global+'box').innerHTML = "";//removing only one box
		}
		
		//boxtest = document.getElementById('boxtest');
		//boxtest.innerHTML = stuff;
	}
}


function messageBox(uplift_str, delete_bt_test, name="", iduser="", id="", usertitle="", time="", message="", uplift="", no_comments=false,comment=false){
		delete_bt = get_delete_bt(delete_bt_test, id, iduser);
		if(no_comments===false){
			comment_btn = "";
		}else{
			//echo '<h1>'.no_comments.'</h1>';
			comment_btn = '<form method="get" action="responsiveHTML5BlogComment.php">'+
							'<input class="_button" type="submit" name="comment_msg"  value="Comment" id="comments"/>'+
							'<div>'+no_comments+' Comments'+'</div>'+
							'<div class="hide">'+
								'<input  class="hide" name="id" value="'+id+'"/>'+//When there is no js.
							'</div>'+
							'</form>';
		}
		if(comment===false){
			comment = "";
			comment_add = "";
		}else{
			comment_add =	'<form method="get" >'+//js= return process. onsubmit="return process(\'AddComment\', \''+id+'\',  \''+iduser+'\', \''+current_user_id_global+'\');"
							'<div class="sm_new_topic lg_new_topic">'+
								'New comment: <br />'+
								'<input type="text" name="get_text" />'+
								'<input class="_button" type="submit" name="new_comment" value="Add" id="comment"/>'+
							'</div>'+
							'<div class="hide">'+
								'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
							'</div>'+
							'</form>';
			}
		
		response = '<div id="'+id+'box'+'" >'+
				'<div class="title">Title: '+usertitle+'</div>'+
				'<p>'+ time+'</p>'+
				'<div class="box">'+
				//'<div class="box2">'+
				'<div>'+ getNameLink(name, iduser)+'</div>'+
				'<div class="message">'+
				message+
				'</div>'+
				'<p />'+
				'<form method="get" onsubmit="return process(\'Uplift\', \''+id+'\',  \''+iduser+'\', \''+current_user_id_global+'\');">'+
				'<div id="'+id+'uplift">'+
				'<input class="_button" type="submit" name="uplift_msg"  value="'+uplift_str+'" id=""/>'+
				'</div>'+
				'<div id="'+id+'">'+uplift+' Amens</div>'+
				'<div class="hide">'+
					'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
				'</div>'+
				'</form>'+
				'<p />'+
				comment_btn+
				'<p />'+

				delete_bt+
				
				comment_add+
				comment+
				'</div>'+//box end
				'</div>';
		return response;
}

function getNameLink(name, iduser){
	name ='<form method="get" action="responsiveHTML5Writers.php">'+
			'<div class="name_button">'+
			'<input class="name_button"  type="submit" name="writer" value="'+name+'" >'+//change if one post e.g love has 1 posts							
			'</div>'+
			//substr(message,0,30).'... click to read more'+
			'<div class="hide">'+
				'<input class="hide" name="writer_id" value="'+iduser+'"/>'+//When there is no js.
			'</div>'+
			'</form>';
	return name;
}

function get_delete_bt(delete_bt_test, id, iduser){
	if(delete_bt_test == "DELETE"){
		delete_bt ='<form method="get" onsubmit="return process(\'DeleteOne\', \''+id+'\', \''+iduser+'\', \''+current_user_id_global+'\');">'+//js= return process.
					'<input class="_button" type="submit" name="delete_msg" value="Delete" id="delete"/>'+
					'<div class="hide">'+
						'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
					'</div>'+
					'</form>';
	}else if(delete_bt_test == "REPORT"){
		delete_bt = '<form method="get" onsubmit="return process(\'ReportOne\', \''+id+'\', \''+iduser+'\', \''+current_user_id_global+'\');">'+//js= return process.
					'<input class="red_button" type="submit" name="report_msg" value="Report" id="report"/>'+
					'<div class="hide">'+
						'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
					'</div>'+
					'</form>';
	}
	return delete_bt;
}


