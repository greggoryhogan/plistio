<?php 
/*
 * Create / Remove User Email account
 */ 

require($_SERVER['DOCUMENT_ROOT'].'/plistio.php');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
if($_POST['passphrase'] != PLISTIO_PASSPHRASE) {
	http_response_code(400);
	echo json_encode(array(
		'message' => 'Tisk tisk tisk.',
	));
} else if(!empty($_POST['email']) && !empty($_POST['password'])) {
	$email = mysqli_real_escape_string($plistio_sql, $_POST['email']);
	$password = mysqli_real_escape_string($plistio_sql, $_POST['password']);
	$query = sprintf("SELECT auth_code FROM users WHERE user_email='%s'", $email);
	// Perform Query
	$result = mysqli_query($plistio_sql,$query);

	//check if user already exists
	if(mysqli_num_rows($result)) {
		http_response_code(400);
		echo json_encode(array(
			'message' => 'This user has been previously registered.',
		));
		die();
	} else {
		//good, lets make them
		$parts = explode("@", $email);
		$username = mysqli_real_escape_string($plistio_sql,$parts[0]);
		$usernameexists = username_exists($username);
		if($usernameexists) {
			$i = 0;
			while(!$usernameexists) {
				$i++;
				$username = $username.$i;
				$usernameexists = username_exists($username);
			}
		}
		$auth_code = randomPassword();

		//insert the user into the db
		$query = sprintf("INSERT INTO users (username, user_email, user_pass, auth_code) VALUES ('%s', '%s', '%s', '%s')", $username, $email, $password, $auth_code);
		$result = mysqli_query($plistio_sql,$query);
		
		if (!$result) {
			http_response_code(400);
			echo json_encode(array(
				'message' => 'Error inserting user.',
			));
		} else {
			http_response_code(200);
			echo json_encode(array(
				'message' => 'Account created successfully.',
				'auth_code' => $auth_code
			));
		}
	} 

} else {
	http_response_code(400);
	echo json_encode(array(
		'message' => 'All fields are required.',
	));
}
exit;
?>