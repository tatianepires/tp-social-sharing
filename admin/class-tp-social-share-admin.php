<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Tp_Social_Share_Admin {

	private $plugin_name;

	private $plugin_nice_name;

	private $version;

	public function __construct( $plugin_name, $plugin_nice_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_nice_name = $plugin_nice_name;
		$this->version = $version;

		add_action( 'carbon_fields_register_fields', array( $this, 'tp_social_share_options' ) );

	}

	public function tp_social_share_options() {
		$description = array(
			'sharing_icons' => __('Select the buttons in the order they should be displayed. Sharing buttons available: Facebook, Twitter, Pinterest, LinkedIn, WhatsApp.'),
		);

		Container::make( 'theme_options', __( 'TP Social Share' ) )
			->set_icon( 'dashicons-share' )
			->add_fields( array(
				// Where to display (content)
				Field::make( 'html', 'tp_social_share_content_display_title' )
					->set_html( sprintf('<h3>%s</h3>', __('Content type options')) ),
				Field::make( 'checkbox', 'tp_social_share_content_display_posts', __('Display on Posts') ),
				Field::make( 'checkbox', 'tp_social_share_content_display_pages', __('Display on Pages') ),
				Field::make( 'checkbox', 'tp_social_share_content_display_cpt', __('Display on Custom Post Types') ),
				// Where to display (location)
				Field::make( 'html', 'tp_social_share_location_display_title' )
				     ->set_html( sprintf('<h3>%s</h3>', __('Location Options')) ),
				Field::make( 'checkbox', 'tp_social_share_location_display_below_title', __('Display on below title') ),
				Field::make( 'checkbox', 'tp_social_share_location_display_after_content', __('Display after content') ),
				Field::make( 'checkbox', 'tp_social_share_location_display_inside_featured_image', __('Display inside featured image') ),
				Field::make( 'checkbox', 'tp_social_share_location_display_float_left', __('Display floating on the left area') ),
				// Buttons
				Field::make( 'html', 'tp_social_share_choices_title' )
				     ->set_html( sprintf('<h3>%s</h3><p>%s</p>', __('Share to'), $description['sharing_icons']) ),
				Field::make( 'multiselect', 'tp_social_share_choices', __('Social networks') )
				     ->add_options( array(
					     'facebook' => 'Facebook',
					     'linkedin' => 'LinkedIn',
					     'pinterest' => 'Pinterest',
					     'twitter' => 'Twitter',
					     'whatsapp' => 'WhatsApp',
				     ) ),
				// Button size
				Field::make( 'html', 'tp_social_share_button_size_title' )
				     ->set_html( sprintf('<h3>%s</h3>', __('Button size')) ),
				Field::make( 'radio', 'tp_social_share_button_size', __('Select size') )
				     ->add_options( array(
					     'small' => __('Small'),
					     'medium' => __('Medium'),
					     'large' => __('Large'),
				     ) ),
				// Button color
				Field::make( 'html', 'tp_social_share_button_color_title' )
				     ->set_html( sprintf('<h3>%s</h3>', __('Button color')) ),
				Field::make( 'radio', 'tp_social_share_button_color', __('Select color') )
				     ->add_options( array(
					     'original' => __('Display icons in their original colors'),
					     'custom' => __('Select color'),
				     ) ),
				Field::make( 'color', 'tp_social_share_button_custom_color', __('Custom color') )
				     ->set_conditional_logic( array(
					     'relation' => 'AND',
					     array(
						     'field' => 'tp_social_share_button_color',
						     'value' => 'custom',
						     'compare' => '=',
					     )
				     ) ),
			) );
	}

}
