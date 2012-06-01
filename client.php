<?


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RezChat Client</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
<script>
var chatting = 0;
var user = 1;
var t;

function reloader(){
	if(chatting === 1){
		//$('#rezChatData').append('bacon');
	}
	t=setTimeout("reloader()",2200);
	runGet();
$('#rezChatData').animate({scrollTop: $('#rezChatData')[0].scrollHeight});
}
function runGet(){
  $.ajax({
	  type: "POST",
	  url: "lib/process.php",
	  dataType: "json",
	  data: { a:chatting, u:user,c:'g'},
	  success:function(data){
		  if(data){
		  if(data.actions != undefined){
			 if(data.actions.force != undefined){ 
			 	startChat();
			 }
		  }	
		  if(chatting === 1){	
		  if(data.messages != undefined){
		  		$.each(data.messages, function(i,mess){
		  $('#rezChatData').append('<div class="chatMessage hidden"><span id="chatPerson">' + mess.name +'</span>: ' + mess.bod + '</div>');
		  		});			 
				$('.chatMessage').fadeIn(300); 
		  }
		  }
		  }
	  }
  });		
	
}

function sendMessage(){
  message = $('input#rezChatMessage').val();
  $('input#rezChatMessage').val('');
  $('#rezChatData').append('<div class="chatMessage"><span id="chatMe">Me</span>: ' + message + '</div>');
  $('#rezChatData').animate({scrollTop: $('#rezChatData')[0].scrollHeight});

  $.ajax({
	  type: "POST",
	  url: "lib/process.php",
	  dataType: "json",
	  data: { a:chatting, u:user, m:message, c:'s'},
	  success:function(data){	

	  }
  });
  return false;			
}


function startChat(){
	chatting = 1;	
	$('#rezChatBox').addClass('chatOpen');
	$('#rezChatInner').show();
}
$(document).ready(function(){
	reloader();
});
</script>
<style>#rezChatBox {
font-family: Arial,'DejaVu Sans','Liberation Sans',Freesans,sans-serif;
	background: 
		-o-linear-gradient(top, #1f84ff 0%, #0458be 100%);
	background: 
		-ms-linear-gradient(top, #1f84ff 0%, #0458be 100%);
	background: 
		-moz-linear-gradient(top, #1f84ff 0%, #0458be 100%);
	background: 
		-webkit-linear-gradient(top, #1f84ff 0%, #0458be 100%);
	background: 
		linear-gradient(top, #1f84ff 0%, #0458be 100%);
	-moz-box-shadow: 2px 2px 3px #333;
	-webkit-box-shadow: 2px 2px 3px #333;
	box-shadow: 2px 2px 3px #333;
	/* For IE 8 */
	-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";
	/* For IE 5.5 - 7 */
	filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000');
	cursor:pointer;
        position:fixed;
        width:280px;
        bottom:0px;
        border-radius: 10px 10px 0 0;
-webkit-border-radius: 10px 10px 0 0;
-moz-border-radius: 10px 10px 0 0;
	-webkit-transition: all .25s ease-in-out;
	-moz-transition: all .25s ease-in-out;
	-o-transition: all .25s ease-in-out;
	transition: all .25s ease-in-out;
}
#rezChatBox  h2 {
margin:0px;
padding:0px;
color:#fff;
text-align:center;
}

#rezChatBox:hover {
padding-bottom:4px;
}

.chatOpen {
height:300px;
cursor:default !important;
}
.chatOpen:hover {
padding-bottom:0px !important;
}

#rezChatData {
width:265px;
background:#fff;
margin:2px 7px;
overflow:auto;
border-radius: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
word-wrap: break-word;
height:235px;
}

#rezChatSend {
margin-left:6px;
}

#rezChatInner {
display:none;
}

span#chatPerson{
color:red;
font-weight:bold;
}

span#chatMe{
color:blue;
font-weight:bold;
}
.chatMessage {
margin:3px;
}
.hidden {
	display:none;
}
</style>
<div id="rezChatBox" onclick="startChat();">
	<h2>Chat with us!</h2>
    <div id="rezChatInner">
    	<div id="rezChatData">
        
        </div>
        <div id="rezChatSend">
        <form id="rezChatForm" onsubmit="sendMessage(); return false;">
        	<input type="text" name="m" id="rezChatMessage" />
        	<input type="submit" id="rezChatSubmit" value="send"/>
        </form>
        </div>
    </div>
</div>



</body>
</html>
