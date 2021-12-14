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
	$query = sprintf("SELECT auth_code FROM users WHERE user_email='%s' AND user_pass='%s'", $email, $password);
	// Perform Query
	$result = mysqli_query($plistio_sql,$query);

	if (!$result) {
		http_response_code(400);
		echo json_encode(array(
			'message' => 'Login invalid.',
		));
	} else {
		while ($row = mysqli_fetch_array($result)) {
			$auth_code = $row['auth_code'];
		}
		http_response_code(200);
		echo json_encode(array(
			'message' => 'Logged in successfully!',
			'auth_code' => (string)$auth_code
		));
	}
	

} else {
	http_response_code(400);
	echo json_encode(array(
		'message' => 'All fields are required.',
	));
}
exit;
?>