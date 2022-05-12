<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Field\Text_Field;

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

	function tp_social_share_options() {
		Container::make( 'theme_options', __( 'TP Social Share' ) )
			->add_fields( array(
				Field::make( 'text', 'tp_social_share_text1', 'Text Field' ),
			) );
	}

}
