<?php

class Tp_Social_Share_Admin {

	private $plugin_name;

	private $plugin_nice_name;

	private $version;

	public function __construct( $plugin_name, $plugin_nice_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_nice_name = $plugin_nice_name;
		$this->version = $version;

	}

}
