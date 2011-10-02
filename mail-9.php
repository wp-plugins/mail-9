<?php
/*
Plugin Name: Mail 9
Plugin URI: http://ideasilo.wordpress.com/
Description: Mail 9 manages your mail queue.
Author: Takayuki Miyoshi
Author URI: http://ideasilo.wordpress.com/
Text Domain: mail9
Domain Path: /languages/
Version: 1.0-dev
*/

/*  Copyright 2011 Takayuki Miyoshi (email: takayukister at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define( 'MAIL9_VERSION', '1.0-dev' );

if ( ! defined( 'MAIL9_PLUGIN_BASENAME' ) )
	define( 'MAIL9_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'MAIL9_PLUGIN_NAME' ) )
	define( 'MAIL9_PLUGIN_NAME', trim( dirname( MAIL9_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'MAIL9_PLUGIN_DIR' ) )
	define( 'MAIL9_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MAIL9_PLUGIN_NAME );

if ( ! defined( 'MAIL9_PLUGIN_URL' ) )
	define( 'MAIL9_PLUGIN_URL', WP_PLUGIN_URL . '/' . MAIL9_PLUGIN_NAME );

if ( ! defined( 'MAIL9_DEFAULT_MAIL_PER_ACT' ) )
	define( 'MAIL9_DEFAULT_MAIL_PER_ACT', 10 );

require_once MAIL9_PLUGIN_DIR . '/settings.php';

?>