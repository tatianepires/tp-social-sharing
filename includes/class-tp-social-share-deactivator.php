<?php

class Tp_Social_Share_Deactivator {

	public static function deactivate() {

		delete_option( '_tp_social_share_content_display_posts' );
		delete_option( '_tp_social_share_content_display_pages' );
		delete_option( '_tp_social_share_content_display_cpt' );
		delete_option( '_tp_social_share_location_display_below_title' );
		delete_option( '_tp_social_share_location_display_after_content' );
		delete_option( '_tp_social_share_location_display_inside_featured_image' );
		delete_option( '_tp_social_share_location_display_float_left' );
		delete_option( '_tp_social_share_button_size' );
		delete_option( '_tp_social_share_button_color' );
		delete_option( '_tp_social_share_button_custom_color' );

		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->options
            			WHERE option_name LIKE %s",
				'_tp_social_share_choices%'
			)
		);
	}

}
