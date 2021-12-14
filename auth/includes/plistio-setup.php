<?php 
/*
 * Set up plistio auth default settings for new env
 */

if(!PLISTIO_SETUP) {
    if ( file_exists( PLISTIO_DIR . 'plistio-config.php' ) ) {
        //set up users db
        $plistio_users = "CREATE TABLE IF NOT EXISTS users (
            id mediumint(8) unsigned NOT NULL auto_increment,
            username varchar(250),
            user_email varchar(250),
            user_pass varchar(250),
            auth_code varchar(500),
            spotify_expiration varchar(100),
            spotify_access_token varchar(500),
            spotify_refresh_token varchar(500),
            user_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )";
        $query = mysqli_query($plistio_sql, $plistio_users);
        if ($query === TRUE) {
            echo '<p>user table created</p>'; 
        } else {
            echo '<p>user table creation error</p>'; 
        }

    } else {
        echo 'No config file present';
    }
}