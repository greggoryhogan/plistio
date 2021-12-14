<?php 
/*
 * Interpret Spotify redirect uri
 */ 

$session = new SpotifyWebAPI\Session(
    PLISTIO_SPOTIFY_CLIENT_ID,
    PLISTIO_SPOTIFY_CLIENT_SECRET,
    PLISTIO_SPOTIFY_REDIRECT_URI
);

if (isset($_GET['code'])) {
    /*$state = $_GET['state'];
    // Fetch the stored state value from somewhere. A session for example
    if ($state !== SPOTIFY_STATE) {
        // The state returned isn't the same as the one we've stored, we shouldn't continue
        //die('State mismatch');
    }*/

    // Request a access token using the code from Spotify
    $session->requestAccessToken($_GET['code']);
    $accessToken = $session->getAccessToken();
    $refreshToken = $session->getRefreshToken();

    // Store the access and refresh tokens somewhere. In a session for example
    $table = $wpdb->prefix.'plistio_auth';
    $data = array( 
        'spotify_code' => $_GET['code'], 
        'spotify_access_token' => $accessToken, 
        'spotify_refresh_token' => $refreshToken, 
    ); 
    $where = array('id' => $GLOBALS['user_id']);
    $wpdb->update($table, $data, $where);
    wp_redirect(get_bloginfo('url').'?connected=1');
    exit;
} else {
    $options = [
        'scope' => [
            'user-read-playback-state',
            'user-modify-playback-state',
            'user-read-currently-playing',
            'user-read-email',
            'playlist-modify-private',
            'playlist-read-collaborative',
            'playlist-read-private',
            'playlist-modify-public'
        ],
    ];
    wp_redirect($session->getAuthorizeUrl($options));
    exit;
}
?>