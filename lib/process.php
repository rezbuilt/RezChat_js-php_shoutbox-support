<?
$PERSON_NAME ='Rez';

require_once("db.php");
$data = array();
if(isset($_POST['c'])){
if($_POST['c'] == 'g'){
	
	$user = $_POST['u'];
	$chatting = $_POST['a'];
	if($chatting == 1){
		
		$getMessages = "SELECT * FROM rez_chat_messages WHERE to_admin = 0 AND sent = 0 AND `foreign` = $user";
		$messOut = dbProcess::queryToArr($getMessages);
		if(isset($messOut[0])){
		$updateMessages = "UPDATE `rez_chat_messages` SET `sent`=1, `modified`= now() WHERE to_admin = 0 AND `foreign` = $user";
		dbProcess::queryData($updateMessages);

			foreach($messOut as $mess){
				$data['messages'][] = array('name'=>$PERSON_NAME,'bod'=>$mess->message);
			}
		}

	} else {
		$checkForce ="SELECT * FROM rez_chat_messages WHERE to_admin = 0 AND sent = 0 AND force_open = 1 AND `foreign` = $user";
		$checkedForce= dbProcess::queryToArr($checkForce);
		if(isset($checkedForce[0])){
			$updateMessages = "UPDATE `rez_chat_messages` SET `sent`=1, `modified`= now() WHERE to_admin = 0 AND force_open = 1 AND `foreign` = $user";
			dbProcess::queryData($updateMessages);
			$data['actions']['force'] = 1;
			foreach($checkedForce as $cF){
				if($cF->message != ''){
					$data['messages'][] = array('name'=>$PERSON_NAME,'bod'=>$cF->message);
				}
			}
		}
		
	}
	$userSave['`foreign`'] = $user;
	$userSave['active'] = $chatting;
	dbProcess::insertData('rez_chat_clients', $userSave);
	
} else if($_POST['c'] == 's'){
	$user = $_POST['u'];
	//$chatting = $_POST['a'];
	$message = $_POST['m'];
	
	$messSave['`foreign`'] = $user;
	$messSave['to_admin'] = 1;
	$messSave['message'] = $message;
	
	dbProcess::insertData('rez_chat_messages', $messSave);


} else if($_POST['c'] == 'ag'){
	
	$user = $_POST['u'];
	$chatting = $_POST['a'];
	if($chatting == 1){
		
		$getMessages = "SELECT * FROM rez_chat_messages WHERE to_admin = 1 AND sent = 0 AND `foreign` = $user";
		$messOut = dbProcess::queryToArr($getMessages);
		if(isset($messOut[0])){
		$updateMessages = "UPDATE `rez_chat_messages` SET `sent`=1, `modified`= now() WHERE to_admin = 1 AND `foreign` = $user";
		dbProcess::queryData($updateMessages);

			foreach($messOut as $mess){
				$data['messages'][] = array('name'=>$PERSON_NAME,'bod'=>$mess->message);
			}
		}

	} else {
		$checkForce ="SELECT * FROM rez_chat_messages WHERE to_admin = 0 AND sent = 0 AND force_open = 1 AND `foreign` = $user";
		$checkedForce= dbProcess::queryToArr($checkForce);
		if(isset($checkedForce[0])){
			$updateMessages = "UPDATE `rez_chat_messages` SET `sent`=1, `modified`= now() WHERE to_admin = 1 AND force_open = 1 AND `foreign` = $user";
			dbProcess::queryData($updateMessages);
			$data['actions']['force'] = 1;
			foreach($checkedForce as $cF){
				if($cF->message != ''){
					$data['messages'][] = array('name'=>$PERSON_NAME,'bod'=>$cF->message);
				}
			}
		}
		
	}
	$userSave['`foreign`'] = $user;
	$userSave['active'] = $chatting;
	dbProcess::insertData('rez_chat_clients', $userSave);
	
} else if($_POST['c'] == 'as'){
	$user = $_POST['u'];
	//$chatting = $_POST['a'];
	$message = $_POST['m'];
	
	$messSave['`foreign`'] = $user;
	$messSave['to_admin'] = 0;
	$messSave['message'] = $message;
	
	dbProcess::insertData('rez_chat_messages', $messSave);


}


	header("Content-type: text/plain");
	echo json_encode($data);

}
?>