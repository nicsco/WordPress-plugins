<?php
/*
Plugin Name: Login Button
Plugin URI: http://nicola.scottidiuccio.com/
Description: Put an (hidden) login button on the top-right page on front end. The button is shown only when mouse goes over it.
Version: 20140212
Author: Nicola Scotti di Uccio
Author URI: http://nicola.scottidiuccio.com/
*/

function lb_logged_in(){
	global $post;

	$icon	=	'<div id="login_button">';
	if ( current_user_can( 'edit_post', $post->ID ) )
		$icon	.=	'<i class="lb-icon-pencil" onclick="location.href=\'' . get_edit_post_link() . '\';"></i>';
	$icon	.=	'<i class="lb-icon-cog" onclick="location.href=\'' . admin_url() . '\';"></i>';
	$icon	.=	'<i class="lb-icon-logout" onclick="location.href=\'' . wp_logout_url() . '\';"></i>';
	$icon	.=	'</div>';

	echo $icon;

}


function login_button_init() {

	if ( is_user_logged_in() ){
		//	HIDE WORDPRESS ADMIN BAR
		add_filter( 'show_admin_bar', '__return_false' );
		add_action( 'wp_footer', 'lb_logged_in' );
	}
	else {
		add_action( 'wp_footer', 'lb_not_logged_in' );
		add_action( 'wp_footer', function(){ echo '<div id="login_button"><i class="lb-icon-login" onclick="location.href=\'' . wp_login_url() . '\';"></i></div>'; } );
	}

	wp_enqueue_style( 'lb-style',	WP_PLUGIN_URL . '/login-button/css/login-button.css',	false, '2', 'all' );

}

add_action( 'init', 'login_button_init' );