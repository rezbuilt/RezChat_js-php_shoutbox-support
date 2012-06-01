<?
//rezChat Install Script
//DB info and general settings


$db_data = array();
$error = array();
$stop = array();

if(isset($_POST['action']) && ($_POST['action'] == 'install')){
	
		//verify DB	
		$user = $_POST['user'];
		$host = $_POST['host'];
		$pass = $_POST['pass'];
		$db = $_POST['database'];
		
		$foreign_table = $_POST['ft'];
		$foreign_primary_key = $_POST['fpk'];
		$foreign_display_field = $_POST['diplay_field'];

		$mysqli = new mysqli($host, $user , $pass, $db);
		if ($mysqli->connect_errno) {
			$stop[] = 'Failed to connect to database!';
		}
		if(empty($stop)){
			$db_data[] = "<?php";
			$db_data[] = ' 
			class dbConfig{
				public static $user = "'. $user  .'";
				public static $host = "'. $host .'";
				public static $db = "'. $db .'";
				public static $pass = "'. $pass .'";
				public static $ft = "'. $foreign_table .'";
				public static $fpk = "'. $foreign_primary_key .'";
				public static $display_field = "'. $foreign_display_field .'";
			}
			
			';
			
			$db_data[] = "?>";
		}
		
	
	if(isset($db_data[0])){
		$fp = fopen("lib/config.php", "w");
		foreach ($db_data as $d){
			fwrite($fp, $d);
		}
		fclose($fp);
	}

//Build Database Tables
	$clientsTBL = "CREATE TABLE rez_chat_clients
	(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`foreign` int(11),
		`active` tinyint(1),
		`open` tinyint(1),
		`created` datetime,
		`modified` datetime,
		 PRIMARY KEY (id)
	)";
	$messagesTBL = "CREATE TABLE rez_chat_messages
	(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`foreign` int(11),
		`message` text,
		`to_admin` tinyint(1),
		`sent` tinyint(1),
		`force_open` tinyint(1),
		`created` datetime,
		`modified` datetime,
		PRIMARY KEY (id)
	)";	
	$mysqli->query($clientsTBL);
	$mysqli->query($messagesTBL);
	
} else {
// initial checks
		if (!function_exists('mysqli_connect')) {
  			$error[] = 'PHP mysqli library is not installed!';
		}  
		
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RezChat Install Script</title>
</head>
<body>
<style>
html, body, div, span, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, code, del, dfn, em, img, q, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
font-family: Arial,'DejaVu Sans','Liberation Sans',Freesans,sans-serif;

}
html {
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
height:100%;
}
#content {
width:600px;
margin-left:auto;
margin-right:auto;
background:#fff;
border:3px solid #ccc;
border-radius:5px;
-moz-border-radius:5px;
-webkit-border-radius:5px;
margin-top:20px;
padding:10px;
}

h2, h3 {
text-align:center;
margin:5px;
}
h2 {
font-size:30px;
}

h4 {
font-weight:normal;
}

#database, #foreign {
margin-left:30px;
margin-right:30px;
border:2px solid #ccc;
background:#f1f1f1;
padding:4px;
margin:5px;
}

input#install {
width:600px;
margin-top:20px;
padding:8px;
font-size:20px;
}

#foreign p {
padding:10px 0;
font-size:14px;
}

</style>
<div id="content">
<h2> RezChat Installer</h2>
<p> RezChat is a light weight MySQL/PHP/jQuery based chat client. Unlike conventional chat support clients this system appends messages to containers instead of realoading iframes. The database handlers connect directly into your databases user table to provide display fields.<p>
<div id="former">
	<form method="post">
    <input type="hidden" name="action" value="install" />
    <div id="database">
    <h3>Database Credentials</h3>
    	<div class="formField">
        	<h4>Database Host</h4>
        	<input type="text" name="host" value="localhost" />
        </div>
    	<div class="formField">
        	<h4>Database User</h4>
        	<input type="text" name="user" />
        </div>
    	<div class="formField">
        	<h4>Database Password</h4>
        	<input type="text" name="pass" />
        </div>           
    	<div class="formField">
        	<h4>Database Name</h4>
        	<input type="text" name="database" />
        </div>                     
    </div>
    <div id="foreign">
    <h3>Foreign Key Database</h3>
    <p>Please provide the table, primary key and display field of the user table. This information will be used to display online users, initiate chats and message people. This is required.</p>
    	<div class="formField">
        	<h4>Foreign Database Name</h4>
        	<input type="text" name="ft" />
        </div>        
    	<div class="formField">
        	<h4>Foreign Database Primary Key</h4>
        	<input type="text" name="fpk" />
        </div>    
    	<div class="formField">
        	<h4>Foreign Database Display Field</h4>
        	<input type="text" name="diplay_field" />
        </div>                   
    </div>
    <input type="submit" value="Install" id="install"/>
	</form>
</div>

</div>


</body>
</html>
