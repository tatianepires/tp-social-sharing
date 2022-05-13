<?php

class Tp_Social_Share_Public {

	private $plugin_name;

	private $plugin_nice_name;

	private $version;

	private $options;

	public function __construct( $plugin_name, $plugin_nice_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_nice_name = $plugin_nice_name;
		$this->version = $version;
		$this->options = array(
			'display_on_posts' => get_option( '_tp_social_share_content_display_posts' ),
			'display_on_pages' => get_option( '_tp_social_share_content_display_pages' ),
			'display_on_cpt' => get_option( '_tp_social_share_content_display_cpt' ),
			'display_below_title' => get_option( '_tp_social_share_location_display_below_title' ),
			'display_after_content' => get_option( '_tp_social_share_location_display_after_content' ),
			'display_inside_featured_image' => get_option( '_tp_social_share_location_display_inside_featured_image' ),
			'display_float_left' => get_option( '_tp_social_share_location_display_float_left' ),
			'social_networks' => $this->get_option_social_networks(),
			'button_size' => get_option( '_tp_social_share_button_size' ),
			'button_use_original_color' => get_option( '_tp_social_share_button_color' ),
			'button_custom_color' => get_option( '_tp_social_share_button_custom_color' ),
		);

		add_filter( 'the_content', array($this, 'add_sharing_buttons_to_content') );
		add_filter( 'post_thumbnail_html', array($this, 'add_sharing_buttons_inside_featured_image') );

	}

	public function add_sharing_buttons_to_content( $content ) {

		$post_id = get_the_ID();
		$post_type = get_post_type($post_id);
		$is_custom_post_type = $this->is_custom_post_type($post_type);
		$buttons = $this->generate_buttons();
		$display_buttons_before = ( $this->display_buttons($post_type, $is_custom_post_type) && $this->options['display_below_title'] == 'yes');
		$display_buttons_after = ( $this->display_buttons($post_type, $is_custom_post_type) && $this->options['display_after_content'] == 'yes');

		$buttons_before = ($display_buttons_before) ? '<h2>Sharing below post title </h2>' : '';
		$buttons_after = ($display_buttons_after) ? '<h2>Sharing after post content</h2>' : '';

		return $buttons_before . $content . $buttons_after;

	}

	public function add_sharing_buttons_inside_featured_image( $html ) {

		$post_id = get_the_ID();
		$has_featured_image = has_post_thumbnail($post_id);
		$display_buttons = ( $this->options['display_inside_featured_image'] == 'yes' );
		$inside_featured_image = ($has_featured_image && $display_buttons) ? '<h2>Sharing inside featured image</h2>' : '';
		return $html . $inside_featured_image;

	}

	private function generate_buttons() {

		$is_floating_left = ( $this->options['display_float_left'] == 'yes' );

		return var_export($this->options['social_networks'], true);
	}

	private function get_option_social_networks() {

		global $wpdb;
		$results = $wpdb->get_results( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name LIKE '_tp_social_share_choices%'", OBJECT );
		$social_networks = array();

		foreach ($results as $result) {
			$social_networks[] = $result->option_value;
		}

		return $social_networks;
	}

	private function is_custom_post_type($post_type) {

		$custom_post_types = get_post_types(array(
			'public'   => true,
			'_builtin' => false
		));

		return (count($custom_post_types) > 0 && in_array($post_type, $custom_post_types));

	}

	private function display_buttons($post_type, $is_custom_post_type) {
		return ( ($post_type == 'post' && $this->options['display_on_posts'] == 'yes') ||
		         ($post_type == 'page' && $this->options['display_on_pages'] == 'yes') ||
		         ($is_custom_post_type && $this->options['display_on_cpt'] == 'yes') );
	}

}
