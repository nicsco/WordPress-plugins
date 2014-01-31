<?php
/*
Plugin Name: Login Button
Plugin URI: http://nicola.scottidiuccio.com/
Description: Put an hidden login button on the top-right page in front end. The button is shown only when mouse goes over it. In front-end, admin bar is hidden.
Version: 20140130
Author: Nicola Scotti di Uccio
Author URI: http://nicola.scottidiuccio.com/
*/

function login_button_init() {

	if ( is_user_logged_in() ){
		add_filter( 'show_admin_bar', '__return_false' );
		add_action( 'wp_footer', function(){ echo '<div id="login_button" onclick="location.href=\'' . admin_url() . '\';"></div>'; } );
	}
	else {
		add_action( 'wp_footer', function(){ echo '<div id="login_button" onclick="location.href=\'' . wp_login_url() . '\';"></div>'; } );
	}

	wp_enqueue_style( 'login-button', WP_PLUGIN_URL . '/login-button/login-button.css', false, '2', 'all' );

}

add_action( 'init', 'login_button_init' );
