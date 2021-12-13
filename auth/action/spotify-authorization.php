<?php 
/*
 * Interpret Spotify redirect uri
 */ 

if(isset($_GET['auth_token'])) {
    $auth_token = $_GET['auth_token'];
    $refreshToken = get_spotify_refresh_token($auth_token);
    if($refreshToken !== false) {
        $return = '';
        $session = new SpotifyWebAPI\Session(
            SPOTIFY_CLIENT_ID,
            SPOTIFY_CLIENT_SECRET,
            SPOTIFY_REDIRECT_URI
        );
        $options = [
            'auto_refresh' => true,
        ];
        
        $session->refreshAccessToken($refreshToken);
        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();
        
        $api = new SpotifyWebAPI\SpotifyWebAPI($options, $session);
        // Set our new access token on the API wrapper and continue to use the API as usual
        $api->setAccessToken($accessToken);
        
        // Call the API as usual
        $me = $api->me();
        $return .= 'Welcome '.$me->display_name.', thanks for authorizing!<br>Here are some playlists you have<br>';
        $spotify_id = $me->id;
        
        $playlists = $api->getUserPlaylists($spotify_id);
        
        foreach ($playlists->items as $playlist) {
            $return .= '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';
        }
        //$newlist = $api->createPlaylist(array('name'=>'Plistio Test','public'=>false));
        //$newlistid = $newlist->id;
        //$return .= $newlistid .' ID!';
        //$api->unfollowPlaylist('5ied7LVuEgl1FkL4RXqzxl');
        //print_r($newlist);

        return $return; 
        

    } else {
        $session = new SpotifyWebAPI\Session(
            SPOTIFY_CLIENT_ID,
            SPOTIFY_CLIENT_SECRET,
            SPOTIFY_REDIRECT_URI
        );
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
        return '<a href="'.$session->getAuthorizeUrl($options).'" class="btn btn-primary">Authorize Spotify</a>';
    }
} else {
    return 'No one is logged in';
}
?>