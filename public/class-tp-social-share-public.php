<?php

class Tp_Social_Share_Public {

	private $plugin_name;

	private $plugin_nice_name;

	private $version;

	private $plugin_dir_path;

	private $plugin_dir_url;

	private $options;

	public function __construct( $plugin_name, $plugin_nice_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_nice_name = $plugin_nice_name;
		$this->version = $version;
		$this->plugin_dir_path = plugin_dir_path( __DIR__ );
		$this->plugin_dir_url = plugin_dir_url( __DIR__ );
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

		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_styles') );

	}

	// TODO: he plugin should also enable a shortcode to include the sharing bar inside a post content

	public function add_sharing_buttons_to_content( $content ) {

		$post_id = get_the_ID();
		$post_type = get_post_type($post_id);
		$is_custom_post_type = $this->is_custom_post_type($post_type);
		$permalink = get_the_permalink($post_id);
		$buttons = $this->generate_buttons($permalink);
		$display_buttons_before = ( $this->display_buttons($post_type, $is_custom_post_type) && $this->options['display_below_title'] == 'yes');
		$display_buttons_after = ( $this->display_buttons($post_type, $is_custom_post_type) && $this->options['display_after_content'] == 'yes');

		$buttons_before = ($display_buttons_before) ? $buttons : '';
		$buttons_after = ($display_buttons_after) ? $buttons : '';

		return $buttons_before . $content . $buttons_after;

	}

	public function add_sharing_buttons_inside_featured_image( $html ) {

		$post_id = get_the_ID();
		$permalink = get_the_permalink($post_id);
		$has_featured_image = has_post_thumbnail($post_id);
		$featured_image_url = ($has_featured_image) ? get_the_post_thumbnail_url($post_id) : '';
		$buttons = $this->generate_buttons($permalink, $featured_image_url);
		$display_buttons = ( $this->options['display_inside_featured_image'] == 'yes' );
		$inside_featured_image = ($has_featured_image && $display_buttons) ? $buttons : '';
		return $html . $inside_featured_image;

	}

	private function generate_buttons($permalink, $image = null) {

		$buttons = array();
		$button_size = $this->options['button_size'];
		$button_use_custom_color = ($this->options['button_use_original_color'] == 'custom');
		$button_custom_color = $this->options['button_custom_color'];
		$button_custom_color_style = ($button_use_custom_color) ? sprintf('style="fill: %s"', $button_custom_color) : '';
		$float_left_style_class = ( $this->options['display_float_left'] == 'yes' )
			? 'class="tp_social_share__buttons_container--float_left"'
			: 'class="tp_social_share__buttons_container"';

		$encoded_url = urlencode($permalink);

		$sharing_links = array(
			'facebook' => sprintf('https://www.facebook.com/sharer/sharer.php?u=%s', $encoded_url),
			'linkedin' => sprintf('https://www.linkedin.com/cws/share?url=%s', $encoded_url),
			'pinterest' => sprintf('https://pinterest.com/pin/create/button/?url=%s', $encoded_url),
			'twitter' => sprintf('https://twitter.com/intent/tweet?url=%s', $encoded_url),
			'whatsapp' => sprintf('whatsapp://send?text=%s', $encoded_url),
		);

		if ($image != null) {
			$encoded_image_url = urlencode($image);
			$sharing_links['pinterest'] .= '&media=' . $encoded_image_url;
		}

		$svg_files_url = array(
			'facebook' => sprintf('%spublic/svg/icon_facebook.svg', $this->plugin_dir_path),
			'linkedin' => sprintf('%spublic/svg/icon_linkedin.svg', $this->plugin_dir_path),
			'pinterest' => sprintf('%spublic/svg/icon_pinterest.svg', $this->plugin_dir_path),
			'twitter' => sprintf('%spublic/svg/icon_twitter.svg', $this->plugin_dir_path),
			'whatsapp' => sprintf('%spublic/svg/icon_whatsapp.svg', $this->plugin_dir_path),
		);

		foreach ($this->options['social_networks'] as $network) {
			$svg_icon = file_get_contents($svg_files_url[$network], FILE_USE_INCLUDE_PATH);

			$buttons[] = sprintf('<a href="%s" class="svg-icons %s %s" %s target="_blank">%s</a>',
								$sharing_links[$network],
								$network,
								$button_size,
								$button_custom_color_style,
								$svg_icon
						);
		}

		$buttons = sprintf('<div %s>%s</div>', $float_left_style_class, implode('', $buttons));

		return $buttons;
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

	function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, sprintf('%spublic/css/tp-social-share-public.min.css', $this->plugin_dir_url), array(), false, 'all' );

	}

}
