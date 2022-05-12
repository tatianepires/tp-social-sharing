<?php
class Tp_Social_Share_Admin {

	private $plugin_name;

	private $plugin_nice_name;

	private $version;

	public function __construct( $plugin_name, $plugin_nice_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_nice_name = $plugin_nice_name;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
	}

	public function add_menu_page() {
		$args = array(
			'page_title' => $this->plugin_nice_name,
			'menu_title' => $this->plugin_nice_name,
			'capability' => 'manage_options',
			'menu_slug' => $this->plugin_name,
			'function' => array( $this, 'admin_page_content' ),
			'icon_url' => 'dashicons-share',
			'position' => 75,
		);
		add_menu_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['menu_slug'], $args['function'], $args['icon_url'], $args['position']);
	}

	public function admin_page_content() {
		echo 'Testing';
	}

}
