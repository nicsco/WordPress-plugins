<?php
/*
Plugin Name: Login Button
Plugin URI: http://nicola.scottidiuccio.com/
Description: Put an (hidden) login button on the top-right page on front end. The button is shown only when mouse goes over it.
Version: 20130114
Author: Nicola Scotti di Uccio
Author URI: http://nicola.scottidiuccio.com/
*/

function login_button_init() {

	/* do not show the button if logged in */
	if ( is_user_logged_in() ) return;

	wp_enqueue_style( 'login-button', WP_PLUGIN_URL . '/login-button/login-button.min.css', false, '2', 'all' );

	add_action( 'wp_footer', 'login_button_html' );
}

function login_button_html() {
	echo '<div id="login_button"><a href="' . wp_login_url( get_bloginfo( 'url' ) ) . '">LOG IN</a></div>';
}



add_action( 'init', 'login_button_init' );