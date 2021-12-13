<?php 
/*
 * Create / Remove User Email account
 */ 

require($_SERVER['DOCUMENT_ROOT'].'/plistio.php');

if(isset($_POST['auth_token'])) {
	$auth_token = $_POST['auth_token'];
	$query = sprintf("SELECT username FROM users WHERE auth_code='%s'", mysqli_real_escape_string($plistio_sql, $auth_token));

	// Perform Query
	$result = mysqli_query($plistio_sql,$query);

	if (!$result) {
		echo json_encode(array(
			'status' => 400,
			'message' => 'No user with that token.',
		));
		die();
	}

	while ($row = mysql_fetch_assoc($result)) {
		$username = $row['username'];
	}
	mysql_free_result($result);
	if(isset($_POST['update-username'])) {
		$new_username = $_POST['update-username'];
		$query = sprintf("UPDATE users SET username='%s' WHERE auth_code='%s'", mysqli_real_escape_string($plistio_sql, $new_username), mysqli_real_escape_string($plistio_sql, $auth_token));
		// Perform Query
		$result = mysqli_query($plistio_sql,$query);
		if (!$result) {
			echo json_encode(array(
				'status' => 400,
				'message' => 'Username update failed.',
			));
			die();
		}
		updateEmail($new_username,$username);
		echo json_encode(array(
			'status' => 200,
			'message' => 'Username updated.',
		));
	}

} else {
	echo json_encode(array(
		'status' => 400,
		'message' => 'No auth token provided.',
	));
}
?>