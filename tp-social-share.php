<?php

/**
 * Plugin Name:       TP Social Share
 * Description:       Display selected social sharing buttons in posts and/or on pages.
 * Version:           0.1.0
 * Author:            Tatiane Pires
 * Author URI:        https://tatianepires.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TP_SOCIAL_SHARE_VERSION', '0.1.0' );

require plugin_dir_path( __FILE__ ) . 'includes/class-tp-social-share.php';

function run_tp_social_share() {
	$plugin = new Tp_Social_Share();
}
run_tp_social_share();
