<?php
/*
 * Functions related to users
 */


/*
 * Change a user email
 */ 
function updateEmail($new_username = NULL,$old_username = NULL) {
	//XMLAPI cpanel client class for creating / deleting email accounts
	require(PLISTIO_DIR . 'includes/xmlapi.php');
	
    $xmlapi = new xmlapi(PLISTIO_IP);
    $xmlapi->password_auth(PLISTIO_CPUN, PLISTIO_CPPW);
    $xmlapi->set_port(PLISTIO_PORT);   
    $xmlapi->set_debug(0);        //output to error file  set to 1 to see error_log.
    $xmlapi->set_output('json');

    $action = $_POST['action'];
    if($new_username != null) {
        $email_pass = randomPassword();
        $email_quota = 10;             // 0 is no quota, or set a number in mb

        $args = array(
            domain => PLISTIO_EMAIL_DOMAIN, 
            email => $username, 
            password => $email_pass, 
            quota => $email_quota
        );
        $result = $xmlapi->api2_query(CPUN, 'Email', 'addpop', $args );

        //print_r($result);            //show the result of your query
    } 
    if($old_username != null) {
        $args = array(
            'domain' => PLISTIO_EMAIL_DOMAIN, 
            'email' => $username
        );
        $result = $xmlapi->api2_query(CPUN, 'Email', 'delpop', $args);
        //print_r($result); 
    }
	
}

/*
 * Generate random password
 */ 
function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 14; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return md5(implode($pass)); //turn the array into a string
} 

/*
 * Check if user has authorized spotify previously
 */ 
function get_spotify_refresh_token($auth_token) {
    $query = sprintf("SELECT spotify_refresh_token FROM users WHERE auth_code='%s'", mysqli_real_escape_string($plistio_sql, $auth_token));
	// Perform Query
	$result = mysqli_query($plistio_sql,$query);
	if (!$result) {
		return false;
	}
    
	while ($row = mysql_fetch_assoc($result)) {
		return $row['spotify_refresh_token'];
	}

}

/*
 * Check if username exists
 */ 
function username_exists($username) {
    global $plistio_sql;

    $query = sprintf("SELECT username FROM users WHERE username='%s'", mysqli_real_escape_string($plistio_sql, $username));
	// Perform Query
	$result = mysqli_query($plistio_sql,$query);
    if (!$result) {
        return false;
    }
    return true;
}