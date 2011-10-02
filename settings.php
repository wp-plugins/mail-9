<?php

require_once MAIL9_PLUGIN_DIR . '/includes/classes.php';
require_once MAIL9_PLUGIN_DIR . '/admin/admin.php';

add_action( 'init', 'mail9_load_plugin_textdomain', 8 );

function mail9_load_plugin_textdomain() {
	load_plugin_textdomain( 'mail9', false, 'mail-9/languages' );
}

add_action( 'init', 'mail9_register_post_types', 9 );

function mail9_register_post_types() {
	$args = array(
		'labels' => array(
			'name' => __( 'Mail', 'mail9' ),
			'singular_name' => __( 'Mail', 'mail9' ),
			'add_new_item' => __( 'Add New Mail', 'mail9' ),
			'edit_item' => __( 'Edit Mail', 'mail9' ),
			'new_item' => __( 'New Mail', 'mail9' ),
			'view_item' => __( 'View Mail', 'mail9' ),
			'search_items' => __( 'Search Mail', 'mail9' ),
			'not_found' => __( 'No mail found.', 'mail9' ),
			'not_found_in_trash' => __( 'No mail found in Trash.', 'mail9' ),
			'menu_name' => __( 'Mail Queue', 'mail9' ) ),
		'capability_type' => 'page',
		'show_ui' => true,
		'show_in_menu' => 'tools.php',
		'supports' => array( 'title' ),
		'register_meta_box_cb' => 'mail9_register_meta_boxes',
		'can_export' => false );

	register_post_type( 'mail9', $args );
}

add_action( 'phpmailer_init', 'mail9_phpmailer_init', 20 );

function mail9_phpmailer_init( $phpmailer ) {
	global $mail9_is_running;

	if ( $mail9_is_running )
		return;

	$phpmailer = new PHPMaileroid( $phpmailer );
}

/* Cron */

add_filter( 'cron_schedules', 'mail9_cron_schedules' );

function mail9_cron_schedules( $schedules ) {
	$schedules = array(
		'minutely' => array( 'interval' => 60, 'display' => 'Once Minutely' ) );

	return $schedules;
}

if ( ! defined( 'WP_INSTALLING' ) && ! wp_next_scheduled( 'mail9_minutely_event' ) )
	wp_schedule_event( time(), 'minutely', 'mail9_minutely_event' );

add_action( 'mail9_minutely_event', 'mail9_dequeue' );

function mail9_dequeue() {
	global $mail9_is_running;

	$options = get_option( 'mail9' );

	if ( ! isset( $options['mail_per_act'] ) )
		$options['mail_per_act'] = MAIL9_DEFAULT_MAIL_PER_ACT;

	$mails = get_posts( array(
		'post_type' => 'mail9',
		'post_status' => 'draft',
		'numberposts' => absint( $options['mail_per_act'] ),
		'orderby' => 'post_modified',
		'order' => 'ASC' ) );

	if ( ! $mails )
		return;

	require_once ABSPATH . WPINC . '/class-phpmailer.php';
	require_once ABSPATH . WPINC . '/class-smtp.php';

	foreach ( $mails as $mail ) {
		$phpmailer = get_post_meta( $mail->ID, '_phpmailer', true );

		if ( ! is_callable( array( $phpmailer, 'Send' ) ) )
			continue;

		$trial_count = absint( get_post_meta( $mail->ID, '_trial_count', true ) );
		update_post_meta( $mail->ID, '_trial_count', $trial_count + 1 );

		$mail9_is_running ? usleep( 10000 ) : ( $mail9_is_running = true );

		try {
			$phpmailer->Send();
		} catch ( Exception $e ) {
			wp_update_post( array( 'ID' => $mail->ID, 'post_status' => 'draft' ) );

			continue;
		}

		wp_update_post( array( 'ID' => $mail->ID, 'post_status' => 'publish' ) );
	}

	$mail9_is_running = false;
}

?>