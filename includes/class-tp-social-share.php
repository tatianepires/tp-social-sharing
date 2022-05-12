<?php

class Tp_Social_Share {

	protected $loader;

	protected $plugin_name;

	protected $plugin_nice_name;

	protected $version;

	private $admin;

	private $public;

	public function __construct() {
		if ( defined( 'TP_SOCIAL_SHARE_VERSION' ) ) {
			$this->version = TP_SOCIAL_SHARE_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'tp-social-share';
		$this->plugin_nice_name = 'TP Social Share';

		$this->load_dependencies();

		add_action( 'after_setup_theme', array($this, 'load_carbon_fields') );

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tp-social-share-admin.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tp-social-share-public.php';

		$admin = new Tp_Social_Share_Admin($this->plugin_name, $this->plugin_nice_name, $this->version);

		$public = new Tp_Social_Share_Public($this->plugin_name, $this->plugin_nice_name, $this->version);

	}

	public function load_carbon_fields() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		\Carbon_Fields\Carbon_Fields::boot();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
