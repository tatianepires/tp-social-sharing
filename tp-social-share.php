<?php

/**
 * Plugin Name:       TP Social Share
 * Description:       Display selected social sharing buttons in posts, pages, and custom post types.
 * Version:           1.0.0
 * Author:            Tatiane Pires
 * Author URI:        https://tatianepires.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TP_SOCIAL_SHARE_VERSION', '1.0.0' );

function activate_tp_social_share() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tp-social-share-activator.php';
	Tp_Social_Share_Activator::activate();
}

function deactivate_tp_social_share() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tp-social-share-deactivator.php';
	Tp_Social_Share_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tp_social_share' );
register_deactivation_hook( __FILE__, 'deactivate_tp_social_share' );

require plugin_dir_path( __FILE__ ) . 'includes/class-tp-social-share.php';

function run_tp_social_share() {
	$plugin = new Tp_Social_Share();
}
run_tp_social_share();
