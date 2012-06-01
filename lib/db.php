<?php 
require_once("config.php");

function s($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sa($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = $input;
    }
    return $output;
}
function cleanInput($input) {

  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );
    $output = preg_replace($search, '', $input);
    return $output;
}

class dbProcess extends dbConfig{
	

function queryToArr($query){
	if ($result = dbProcess::queryData($query)) {
		while ($row = mysqli_fetch_object($result)) {
			if ($row) {
				$resultarray[] = $row;
			}
		}
	}
	return $resultarray;	
}

function get_data_row($query) {
	if ($result = dbProcess::queryData($query)) {
		$row = mysqli_fetch_object($result);
		return $row;
	} else {
		return false;	
	}
}


function insertData($table, $data){
	$mysqli = new mysqli(dbProcess::$host, dbProcess::$user, dbProcess::$pass, dbProcess::$db);
	$queryVals = dbProcess::extractFieldValues($data, 'add');
	$sql = "insert into $table " . $queryVals['fields'] . " values " . $queryVals['values']; 
	 if ($mysqli->query($sql)) {
  		return $mysqli->insert_id;
	}else{
		return "fail";
	}
	
}


function queryData($sql){
	$mysqli = new mysqli(dbProcess::$host, dbProcess::$user, dbProcess::$pass, dbProcess::$db);
 	if ($result = $mysqli->query($sql)) {
  		return $result;
		$result->close();
	}	
} 

function extractFieldValues($data, $function) {

	$ret = array();
	$fields = "";
	$values = "";
	foreach ($data as $key => $value) {
		$value = $value;
		$fields .=  $key . ', ';
		$values .=  "'$value', ";
	}
	if ($function=='add') {
		$fields .= 'created, ';
		$values .= 'now(), ';
	}

	$fields .= 'modified';
	$values .= 'now()';
	
	$ret['fields'] = "($fields)";
	$ret['values'] = "($values)";
	return $ret;
}

}
?>