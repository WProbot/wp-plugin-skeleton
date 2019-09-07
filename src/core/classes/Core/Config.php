<?php

namespace src_namespace__\Core;

use src_namespace__\Utils\Immutable_Data_Store;
use src_namespace__\functions as h;

class Config {
	protected static $options = null;

	public static function get_options () {
		if ( null === self::$options ) {
			self::$options = new Immutable_Data_Store();
		}
		return self::$options;
	}

	public static function setup ( $main_file ) {
		$options = self::get_options();
		$root = \dirname( $main_file );
		$plugin_config = require $root . '/config.php';
		$plugin_data = \get_file_data( $main_file, [ 'plugin name', 'version' ] );
		$plugin_name = $plugin_data[0];
		$plugin_version = $plugin_data[1];

		// set plugin main file and root dir
		$plugin_config['MAIN_FILE'] = $main_file;
		$plugin_config['ROOT_DIR'] = $root;

		// set plugin name
		$plugin_config['NAME'] = $plugin_name;

		// set plugin version
		$plugin_config['VERSION'] = $plugin_version;

		// set slug
		if ( ! isset( $plugin_config['SLUG'] ) ) {
			$plugin_config['SLUG'] = h\str_slug( $plugin_name );
		}

		// set prefix
		if ( ! isset( $plugin_config['PREFIX'] ) ) {
			$plugin_config['PREFIX'] = h\str_slug( $plugin_name, '_' ) . '_';
		}

		// set root namespace
		$plugin_config['NAMESPACE_BASE'] = \preg_replace( '/\\\Core$/', '', __NAMESPACE__ );

		foreach ( $plugin_config as $key => $value ) {
			$options->set( $key, $value );
		}
	}

	public static function set ( $key, $value ) {
		h\throw_if( null === $value, "Can't store 'null'." );
		return self::get_options()->set( $key, $value );
	}

	public static function get ( $key, $default = null ) {
		if ( self::get_options()->has( $key ) ) {
			return self::get_options()->get( $key );
		}
		h\throw_if( null === $default, "Not found '$key' key." );
		return $default;
	}
}
