<?php

require_once ABSPATH . WPINC . '/class-phpmailer.php';
require_once ABSPATH . WPINC . '/class-smtp.php';

class PHPMaileroid extends PHPMailer {

	public $phpmailer;

	public function __construct( $phpmailer ) {
		$props = get_object_vars( $phpmailer );

		foreach ( $props as $prop => $value )
			$this->{$prop} = $value;

		$this->phpmailer = $phpmailer;
	}

	public function Send() {
		global $mail9_is_running;

		$mail9_is_running = true;

		$phpmailer = $this->phpmailer;

		$post_id = wp_insert_post(
			array(
				'post_status' => 'draft',
				'post_type' => 'mail9',
				'post_title' => $phpmailer->Subject,
				'post_content' => '' ) );

		if ( $post_id ) {
			update_post_meta( $post_id, '_phpmailer', $this->phpmailer );
		}

		$mail9_is_running = false;
	}
}

?>