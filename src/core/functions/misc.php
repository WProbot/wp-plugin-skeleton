<?php

namespace src_namespace__\functions;

function is_function ( $func ) {
	return is_object( $func ) && $func instanceof \Closure;
}

function get ( $value, $default = null ) {
	$result = is_function( $value ) ? $value() : $value;
	return ! empty( $result ) ? $result : $default;
}

function value ( $value, $default = null ) {
	\error_log( '[WARNING] ' . __FUNCTION__ . ' is deprecated! Use "h\get()" (in core/functions/misc.php) instead.' );
	return get( $value, $default );
}

function maybe_define ( $key, $value = true, $force_upper_case = true ) {
	$key = $force_upper_case ? \strtoupper( $key ) : $key;
	if ( ! defined( $key ) ) {
		define( $key, $value );
	}
}

function get_defined ( $key, $default = false ) {
	if ( defined( $key ) ) {
		return constant( $key );
	}
	return $default;
}

function get_class_constants ( $class_name ) {
	$reflect = new \ReflectionClass( $class_name );
	return $reflect->getConstants();
}

function format ( ...$args ) {
	$message = '';

	foreach( $args as $arg ) {
		if ( null === $arg ) {
			$message .= 'Null';
		}
		elseif ( \is_bool( $arg ) ) {
			$message .= $arg ? 'True' : 'False';
		}
		elseif ( ! \is_string( $arg ) ) {
			$message .= \print_r( $arg, true );
		} else {
			$message .= $arg;
		}
		$message .= ' ';
	}

	return $message;
}

function build_tag_attributes ( $atts_array ) {
	$result = '';
	foreach ( $atts_array as $key => $value) {
		$result .= ' ' . \esc_html( $key ) . '=' . str_add_quotes( \esc_attr( $value ) );
	}
	return \trim( $result );
}

function add_plugin_action_link ( $label, $url, $atts_array = [], $priority = 10 ) {
	$label = \esc_html( $label );
	$url = \esc_attr( $url );
	$atts = build_tag_attributes( $atts_array );
	$link = "<a href='$url' $atts>$label</url>";
	\add_filter(
		'plugin_action_links_' . \plugin_basename( config_get( 'MAIN_FILE' ) ),
		return_push_value( $link ),
		$priority
	);
}

function ns ( $include ) {
	return config_get( 'NAMESPACE_BASE' ) . $include;
}
