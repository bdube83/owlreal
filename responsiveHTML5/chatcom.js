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
	if(current_user_id == '' || !current_user_id){
		window.location.href = "https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogLogIn.php";//cannot go back.$	
		return;
	}
	console.log(current_user_id);
	if(action=="Uplift"){
		document.getElementById(msg_id).innerHTML = '<i class="fa fa-spinner fa-pulse"></i> processing...';
		
		setTimeout(function(){ 
			document.getElementById(msg_id).innerHTML  = "";
		}, 3000);
	}
	if(action=="UpliftComment"){
		document.getElementById(msg_id+'comment').innerHTML = '<i class="fa fa-spinner fa-pulse"></i> processing...';
		setTimeout(function(){ 
			document.getElementById(msg_id+'comment').innerHTML  = "";
		}, 3000);
	}	
	
	if(action=="ReportOne"){
		document.getElementById(msg_id+'reportP').innerHTML = '<i class="fa fa-spinner fa-pulse"></i> sending report...';
		setTimeout(function(){ 
			document.getElementById(msg_id+'reportP').innerHTML  = "Done. We will review this post.";
			setTimeout(function(){ 
				document.getElementById(msg_id+'reportP').innerHTML  = "";
			}, 3000);
		}, 3000);
	}
	if(action=="ReportOneComment"){
		document.getElementById(msg_id+'reportPC').innerHTML = '<i class="fa fa-spinner fa-pulse"></i>  sending report...';
		setTimeout(function(){ 
			document.getElementById(msg_id+'reportPC').innerHTML  = "Done. We will review this comment.";
			setTimeout(function(){ 
				document.getElementById(msg_id+'reportPC').innerHTML  = "";
			}, 3000);
		}, 3000);
	}
	
	if(action=="DeleteOne"){
			document.getElementById(msg_id+'deleteP').innerHTML = '<i class="fa fa-spinner fa-pulse"></i> deleting...';;//removing only one box
		}
	if(action=="DeleteOneComment"){
			document.getElementById(msg_id+'deletePC').innerHTML = '<i class="fa fa-spinner fa-pulse"></i> deleting...';;//removing only one box
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
			
			xmlHttp.open("GET","https://screengrap-bdube83.c9users.io/responsiveHTML5/chat.php?"+parameters,true);
			xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//xmlHttp.send(parameters);
			xmlHttp.onreadystatechange = setTimeout(handleServerResponse, 2000);
			xmlHttp.send(null);

		}catch(e){
			console.log(e.toString());

			//alert(e.toString() );
		}
	}
	return false;
}

function handleServerResponse(){
	//boxtest = document.getElementById('boxtest');
	if(xmlHttp.readyState==4){
		console.log("State 4: sever connection established");
		//boxtest.innerHTML = "State 4: sever connection established";
		if(xmlHttp.status==200){
			try{
				if(mode_global =="SendAndRetrieveNew" ||  mode_global =="More_messages" || mode_global =="MoreMy_messages" ||  mode_global =="MoreMyP_messages"){
					handleResponse();
				}else{
					handleResponseUplift();//checks if user uplifts a message
				}
			}
			catch(e){
				console.log(e.toString());
			}
			
		}else{
			confirm.log(xmlHttp.statusText);
		}
	}else{
		setTimeout('handleServerResponse()',1000);
	}
}

function handleResponseUplift(){
	if(xmlHttp.overrideMimeType){
		//xmlHttp.overrideMimeType("text/xml");

		var xmlResponse = xmlHttp.responseXML;
		var root = xmlResponse.documentElement;
		var msg_ids= root.getElementsByTagName('id');
		var uplifts = root.getElementsByTagName('uplift');
		var uplift_strs = root.getElementsByTagName('uplift_str');
		var report_strs = root.getElementsByTagName('report_str');
		if(mode_global=="UpliftComment"){
			var uplift_strsC = root.getElementsByTagName('uplift_strC');
		}		
		if(mode_global=="ReportOneComment"){
			var report_strCs = root.getElementsByTagName('report_strC');
		}
		console.log(root);
		for(var i=0; msg_ids.length>i;i++){

			var msg_id = msg_ids.item(i).firstChild.data;
			var uplift = uplifts.item(i).firstChild.data;
			if(mode_global=="Uplift"){
				var uplift_str = uplift_strs.item(i).firstChild.data;
			}
			if(mode_global=="UpliftComment"){
				var uplift_strC = uplift_strsC.item(i).firstChild.data;
			}			
			if(mode_global=="ReportOneComment"){
				var report_strC = report_strCs.item(i).firstChild.data;
				document.getElementById(msg_id+'reportPC').innerHTML ='';
				document.getElementById(msg_id_global+"reportC").innerHTML = 
																			'<button type="submit" class="_button">'+
																				'<i class="fa fa-exclamation-triangle"></i> '+report_strC+
																			'</button>';
			}
			if(mode_global=="ReportOne" && msg_id_global==msg_id){
				var report_str = report_strs.item(i).firstChild.data;
				document.getElementById(msg_id_global+'reportP').innerHTML ='';
				document.getElementById(msg_id_global+"report").innerHTML = 
																			'<button type="submit" class="_button">'+
																				'<i class="fa fa-exclamation-triangle"></i> '+report_str+
																			'</button>';
			}
			console.log("hey");
			if(mode_global=="Uplift" && msg_id_global==msg_id ){
				document.getElementById(msg_id_global).innerHTML = '';
				document.getElementById(msg_id_global+"uplift").innerHTML =
																			'<button type="submit" class="_button">'+
																							'<i class="fa fa-child fa-lg"></i>'+' '+
																							uplift+' '+uplift_str+
																			'</button>';
			}
			if(mode_global=="UpliftComment"){
				document.getElementById(msg_id+'comment').innerHTML ='';
				document.getElementById(msg_id_global+"upliftC").innerHTML = 
																			'<button type="submit" class="_button">'+
																							'<i class="fa fa-child fa-lg"></i>'+' '+
																							uplift+' '+uplift_strC+
																			'</button>';
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
		//xmlHttp.overrideMimeType("text/xml");
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
		var propics = root.getElementsByTagName('propic');
		var topics = root.getElementsByTagName('topic');
		var target_img_boxs = root.getElementsByTagName('target_img_box');
		var no_comments = root.getElementsByTagName('no_comments');
		var response = "";
		//var stuff = "";
		for(var i=0;messages.length>i;i++){
			var title = titles.item(i).firstChild.data;
			try{
				var message = messages.item(i).firstChild.data;
			}catch(e){
				message = "";
			}
		    var time = times.item(i).firstChild.data;
		    var uplift = uplifts.item(i).firstChild.data;
			var msg_id = msg_ids.item(i).firstChild.data;
			var iduser = idusers.item(i).firstChild.data;
			var name = names.item(i).firstChild.data;
			var uplift_str = uplift_strs.item(i).firstChild.data;
			var delete_bt_test = delete_bts.item(i).firstChild.data;
			var propic = propics.item(i).firstChild.data;
			var topic = topics.item(i).firstChild.data;
			var target_img_box = target_img_boxs.item(i).firstChild.data;
			var no_comment = no_comments.item(i).firstChild.data;
			response = messageBox(uplift_str, delete_bt_test, name, iduser, msg_id, title, time, message, uplift, no_comment, false, propic, target_img_box, topic) + response;
			if(mode_global=="Uplift"){
				document.getElementById(msg_id).innerHTML = uplift+' Wow';
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


function messageBox(uplift_str, delete_bt_test, name, iduser, id, usertitle, time, message, uplift, no_comments, comment, propic, target_img_box, topic){
		var delete_bt = get_delete_bt(delete_bt_test, id, iduser);
		var privatetab = "";
		if(topic == 'true'){
			privatetab = "<div id='privatetab'>Private</div>";
		}
		if(no_comments===false){
			var comment_btn = "";
		}else{
			//echo '<h1>'.no_comments.'</h1>';
			comment_btn = '<form method="get" action="responsiveHTML5BlogComment.php">'+
							'<button type="submit" class="_button">'+
								'<i class="fa fa-comments fa-lg"></i>'+' '+
								no_comments+' comments'+
							'</button>'+
							'<div class="hide">'+
								'<input  class="hide" name="id" value="'+id+'"/>'+//When there is no js.
							'</div>'+
							'</form>';
		}
		if(comment===false){
			comment = "";
			var comment_add = "";
		}else{
			comment_add =	'<form method="get" >'+//js= return process. onsubmit="return process(\'AddComment\', \''+id+'\',  \''+iduser+'\', \''+current_user_id_global+'\');"
							'<div>'+
								'New comment: <br />'+
								'<input type="text" name="get_text" />'+
								'<input class="_button" type="submit" name="new_comment" value="Add" id="comment"/>'+
							'</div>'+
							'<div class="hide">'+
								'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
							'</div>'+
							'</form>';
		}	
		var response = '<div id="'+id+'box'+'" >'+
				'<div class="box">'+
				//'<div class="box2">'+
				'<form method="get" action="responsiveHTML5Writers.php">'+
				'<div>'+
				'<input value="" type="image" src="'+propic+'"  style="width:50px;height:40px;float:left">'+
				getNameLink(name, iduser)+'</div>'+
				'</form>'+
				'<p>'+ time+'</p>'+
				privatetab+
				'<div class="message">'+
				'<div class="inner_title">'+usertitle+'</div>'+												
				message+
				target_img_box+
				'</div>'+
				'<p />'+
				'<div style="display:flex" >'+
				'<form method="get" onsubmit="return process(\'Uplift\', \''+id+'\',  \''+iduser+'\', \''+current_user_id_global+'\');">'+
				'<div id="'+id+'uplift">'+
				'<button type="submit" class="_button">'+
								'<i class="fa fa-child fa-lg"></i>'+' '+
								uplift+' '+uplift_str+
				'</button>'+
				'</div>'+
				'<div id="'+id+'"></div>'+
				'<div class="hide">'+
					'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
				'</div>'+
				'</form>'+
				'<p />'+
				comment_btn+
				'<p />'+

				delete_bt+
				'<div class="fb-share-button"'+ 
					'data-href="https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogComment.php?id="'+id+'"'+
				'data-layout="button_count">'+
				'</div>'+
				'</div>'+
				
				comment_add+
				comment+
				'</div>'+//box end
				'</div>';
		return response;
}

function getNameLink(name, iduser){
	name =
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
	comment="";
	report="report";
	if(delete_bt_test == "DELETE"){
		delete_bt ='<form method="get" onsubmit="return process(\'DeleteOne\', \''+id+'\', \''+iduser+'\', \''+current_user_id_global+'\');">'+//js= return process.
					'<button type="submit" class="_button">'+
						'<i class="fa fa-trash-o  fa-lg"></i>'+' Delete'+
					'</button>'+		
					'<div id="'+id+'deleteP'+comment+'"></div>'+
					'<div class="hide">'+
						'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
					'</div>'+
					'</form>';
	}else if(delete_bt_test == "REPORT"){
		delete_bt = '<form method="get" onsubmit="return process(\'ReportOne\', \''+id+'\', \''+iduser+'\', \''+current_user_id_global+'\');">'+//js= return process.
					'<div id="'+id+'report">'+
					'<button type="submit" class="_button">'+
						'<i class="fa fa-exclamation-triangle"></i> '+report+
					'</button>'+
					'<div id="'+id+'reportP'+comment+'"></div>'+
					'</div>'+
					'<div class="hide">'+
						'<input class="hide" name="id" value="'+id+'"/>'+//When there is no js.
					'</div>'+
					'</form>';
	}
	return delete_bt;
}

/*function pasteGrab() {
	var succeed = document.execCommand("paste");
	console.log(succeed);
}*/
var imgnum = 1;
var imgurl;
var base64data;

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                // $('#imgurl')
                //     .attr('src', e.target.result)
                //     .width(150)
                //     .height(200);
                    
                    var blob = e.target.result;
    		        window.URL = window.URL || window.webkitURL;
    		        var blobUrl = blob;
                    
                    
                    var img = document.createElement('img');
       		        img.src = blobUrl;
       		        
       		        $( "#log-box img" ).remove();
       		        	
    		        var logBox = document.getElementById('log-box');
	        		logBox.appendChild(img);
                  	document.getElementById("imgurl").innerHTML = '<input class="hide" id="imgurl" name="imgurl" value="'+blobUrl+'"/>';
                  	imgurl = blobUrl;
                  	base64data = reader.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }


// Add the paste event listener
window.addEventListener("paste", function (e) {
	console.log(imgnum);
	// We need to check if event.clipboardData is supported (Chrome)
	if (e.clipboardData) {
		// Get the items from the clipboard
		var items = e.clipboardData.items;
		for (var i = 0; i < items.length; ++i) {
		
		    if (items[i].kind == 'file' &&
		        items[i].type.indexOf('image/') !== -1) {
				console.log("hey");
		        var blob = items[i].getAsFile();
		        window.URL = window.URL || window.webkitURL;
		        var blobUrl = window.URL.createObjectURL(blob);
		
		        var img = document.createElement('img');
		        
		        var reader = new window.FileReader();
 				reader.readAsDataURL(blob); 
 				reader.onloadend = function() {
	                base64data = reader.result;                
	                console.log(base64data );
				}
		        
		        img.src = blobUrl;
		        var logBox = document.getElementById('log-box');
		        if(imgnum == 1){
		        	logBox.appendChild(img);
                  	document.getElementById("imgurl").innerHTML = '<input class="hide" id="imgurl" name="imgurl" value="'+blobUrl+'"/>';
        			imgurl = blobUrl;
        			imgnum = 2;
		        }
		        
		    }
		
		}

   // If we can't handle clipboard data directly (Firefox), 
   // we need to read what was pasted from the contenteditable element
	} else {
		// This is a cheap trick to make sure we read the data
		// AFTER it has been inserted.
		//setTimeout(checkInput, 1);
		console.log("hey2");
   }
});
var privatePost = false;
function uploadImageP(){
	privatePost = true;
	uploadImage();
}


function uploadImage(){
	jQuery(document).ready(function(){
        var writer_id_img = document.getElementById('writer_id_img').value;
        var title = document.getElementById('title').value;
        var profileJSON = {'writer_id_img' : writer_id_img,
                             'imgurl'   : imgurl,
                             'title' : title,
                             'privatePost' : privatePost,
                             'base64data' : base64data
        	
        };

		// Using JSONP to connect to register_user.php
		$.ajax({
		  url: "https://screengrap-bdube83.c9users.io/responsiveHTML5/uploadTitlePic.php",
		          
		     //prepering data to send.
		  type: 'POST',
		  data: profileJSON,
		  
		  //contentType: 'application/json; charset=utf-8',
		  
		  // Tell jQuery we're expecting JSON
		  dataType: "json", 
		  
		  
		  // Work with the response
		  success: function( response_login ) {
		      console.log(response_login);
		      if(privatePost == true) {
	          	window.location.href = "https://screengrap-bdube83.c9users.io/responsiveHTML5/responsiveHTML5BlogPrivateWall.php";//cannot go back.
		      }else{
            	window.location.href = "https://screengrap-bdube83.c9users.io/responsiveHTML5/index.php";//cannot go back.$
		      }
	          //window.location.href = "transport_booking.php"; //can go back.
	
		  },
		  error: function (request, status, error) {
		      console.log(request.responseText);
		      console.log(error);
		      console.log(status);
		  }
		});
	});
	
}
 
/* Parse the input in the paste catcher element */
function checkInput() {
   // Store the pasted content in a variable
   var child = pasteCatcher.childNodes[0];
 
   // Clear the inner html to make sure we're always
   // getting the latest inserted content
   pasteCatcher.innerHTML = "";
    
   if (child) {
      // If the user pastes an image, the src attribute
      // will represent the image as a base64 encoded string.
      if (child.tagName === "IMG") {
         createImage(child.src);
      }
   }
}
 
/* Creates a new image from a given source */
function createImage(source) {
   var pastedImage = new Image();
   pastedImage.onload = function() {
      // You now have the image!
   }
   pastedImage.src = source;
}

function removeTags(){
    var txt = document.getElementById('myString').value;
    var rex = /(<([^>]+)>)/ig;
    alert(txt.replace(rex , ""));

}


