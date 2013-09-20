<?php
/*
 //Copyright (c) 2013 Jason Kennaly.
//All rights reserved. This program and the accompanying materials
//are made available under the terms of the GNU Affero General Public License v3.0 which accompanies this distribution, and is available at
//http://www.gnu.org/licenses/agpl.html
//
//Contributors:
//    Jason Kennaly - initial API and implementation
*/


?>


<?php 


include('../includes/content/blocks/db_functions.php');

$post = json_decode($_POST['json'], true);
//$post = $_POST;

//Tag, UID and auth_key all need to be present. If unknown, set tag to login or register


include('../variables/variables.php');

$master = mysql_connect($dbhost,$master_dbuser,$master_dbpw);
@mysql_select_db($master_db, $master) or die( "Unable to select master database");



include('../includes/check_rights.php');
include('../includes/content/blocks/database_functions.php');
include('../includes/content/blocks/other_functions.php');

$db = new DB_Functions();

// response Array
$response = array("tag" => $post['tag'], "success" => 0, "error" => 0);

if ((isset($post['tag']) && $post['tag'] != '') &&
(isset($post['auth_key']) && $post['auth_key'] != '') &&
(isset($post['uid']) && $post['uid'] != '')) {
	if ((!empty($post['username']) xor !empty($post['email'])) && $post['tag'] == "login"){
		// Request type is check Login
		$email = $post['email'];
		$name = $post['username'];
		$password = $post['password'];

		// check for user
		//($email, $name, $password, $master);
		$user = $db->getUserByEmailAndPassword($_SERVER['REMOTE_ADDR'], $post['claimedip'], $post['tag'], $email, $name, $password, $master);
		if ($user != false) {
			// user found
			$response["success"] = 1;
			$response["uid"] = $user["id"];
			$response["auth"] = $user["mobile_auth_key"];
			$response["debug"] = $user["debug"];
			$response["name"] = $user["username"];
			$response["email"] = $user["email"];
			$response["level"] = $user["level"];
			$response["follows"] = $user["follows"];
			die( json_encode($response));
		} else {
			// user not found
			$response["error"] = 1;
			$response["error_msg"] = "Incorrect email or password!";
			die( json_encode($response));
		}

	} else if (!empty($post['username']) && !empty($post['email']) && $post['tag'] == "login"){
		// Request type is Register new user
		$name = $post['username'];
		$email = $post['email'];
		$password = $post['password'];

		// check if user is already existed
		if ($db->isUserExisted($email, $name, $master)) {
			// user is already existed - error response
			$response["error"] = 2;
			$response["error_msg"] = "User already existed";
			die( json_encode($response));
		} else {
			// store user
			$user = $db->storeUser($_SERVER['REMOTE_ADDR'], $post['claimedip'], $post['tag'], $name, $email, $password, $master, $outlawcharacters);
			if (empty($user['error']) && !empty($user)) {
				// user stored successfully
				$response["success"] = 1;
				$response["uid"] = $user["id"];
				$response["auth"] = $user["mobile_auth_key"];
				if(!empty($user["debug"])) $response["debug"] = $user["debug"];
				$response["name"] = $user["username"];
				$response["email"] = $user["email"];
				$response["level"] = $user["level"];
				$response["follows"] = $user["follows"];
				die( json_encode($response));
			} else if (empty($user)) {
				// user failed to store
				$response["error"] = 3;
				$response["error_msg"] = "Error occured in Registration: tag:'".$post['tag']."' username:'".$post['username']."' email:'".$post['email']."'";
				die( json_encode($response));
			} else {
				$response["error"] = $user['error'];
				$response["error_msg"] = $user["error_msg"];
				die( json_encode($response));
			}
		}



	} else if(!$db->userAuthOK($post['uid'], $post['auth_key'], $master)) {
		$response['error_msg'] = "Invalid UID or key: tag:'".$post['tag']."' uid:'".$post['uid']."' auth_key:'".$post['auth_key'];
		$response['error']= 1;
		die( json_encode($response));
	}
} else{
	$response['error_msg'] = "Invalid Request: tag:'".$post['tag']."' uid:'".$post['uid']."' auth:'".$post['auth']."' auth_key:'".$post['auth_key'];
	$response['error']= 1;
	die( json_encode($response));
}

if(empty($post['db'])) $post['db'] = 0;

if ($post['db'] > 0) {
	$_SESSION['fest'] =  $post['db'];

	include('../variables/fest_variables.php');

	$main = mysql_connect($dbhost,$dbuser,$dbpw);
	@mysql_select_db($dbname, $main) or die( "Unable to select main database");
	$user = $post['uid'];

	include('../variables/page_variables.php');
}

$db->storeUserAccess($_SERVER['REMOTE_ADDR'], $post['claimedip'], $post['tag'], $master, $post['uid'], $post['auth_key'], $post['db']);

switch ($post['tag']){
	case "getFullMasterTable":
		$response = $db->getFullTable($master, $master, $post['table'], $post['uid'] );
		if($response["error"] == 0) $response["success"] = 1;
		$response['festid'] = $post['db'];
		break;
	case "getFullFestTable":
		$response = $db->getFullTable($main, $master, $post['table'], $post['uid'] );
		if($response["error"] == 0) $response["success"] = 1;
		$response['festid'] = $post['db'];
		$response['table'] = $post['table'];
		break;
	case "getFestTableList":
		$response = $db->getTableList($main);
		if($response["error"] == 0) $response["success"] = 1;
		$response['festid'] = $post['db'];
		break;
		/*
	case "getFullFestDB":
		$response['tables'] = $db->getFullDB($main);
		$response['festid'] = $post['db'];
		if($response["error"] == 0) $response["success"] = 1;
		break;
		*/
	default:
		$response["error"] = 10;
		$response["error_msg"] = "The tag could not be identified.";
		break;
}


die( json_encode($response));
?>
