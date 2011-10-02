<?php

add_action( 'admin_init', 'mail9_admin_init' );

function mail9_admin_init() {
	register_setting( 'general', 'mail9', 'mail9_options_validate' );

	add_settings_field( 'mail9_mail_per_act', __( 'Mail Settings', 'mail9' ),
		'mail9_settings_field_mail_per_act', 'general' );
}

function mail9_settings_field_mail_per_act() {
	$options = get_option( 'mail9' );

	if ( ! isset( $options['mail_per_act'] ) )
		$options['mail_per_act'] = MAIL9_DEFAULT_MAIL_PER_ACT;

	$field = '<input type="text" class="small-text" id="mail9_mail_per_act" name="mail9[mail_per_act]" value="' . absint( $options['mail_per_act'] ) . '" />';

	echo '<p>' . sprintf( __( 'Send %s mail per minute', 'mail9' ), $field ) . '</p>';
}

function mail9_options_validate( $input ) {
	$newinput['mail_per_act'] = absint( $input['mail_per_act'] );

	return $newinput;
}

function mail9_register_meta_boxes() {
	add_meta_box( 'mail9_message_header', __( 'Message Header', 'mail9' ),
		'mail9_message_header_meta_box', 'mail9', 'normal', 'default' );

	add_meta_box( 'mail9_message_body', __( 'Message Body', 'mail9' ),
		'mail9_message_body_meta_box', 'mail9', 'normal', 'default' );
}

function mail9_message_header_meta_box( $post ) {
	require_once ABSPATH . WPINC . '/class-phpmailer.php';
	require_once ABSPATH . WPINC . '/class-smtp.php';

	$phpmailer = get_post_meta( $post->ID, '_phpmailer', true );

	if ( ! is_a( $phpmailer, 'PHPMailer' ) )
		return;

	echo '<pre>' . esc_html( $phpmailer->CreateHeader() ) . '</pre>';
}

function mail9_message_body_meta_box( $post ) {
	require_once ABSPATH . WPINC . '/class-phpmailer.php';
	require_once ABSPATH . WPINC . '/class-smtp.php';

	$phpmailer = get_post_meta( $post->ID, '_phpmailer', true );

	if ( ! is_a( $phpmailer, 'PHPMailer' ) )
		return;

	echo '<pre>' . esc_html( $phpmailer->Body ) . '</pre>';
}

?>