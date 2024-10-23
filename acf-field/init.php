<?php
/**
 * Registration logic for the new ACF field type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'boo_acf_pb_include_acf_field_page_builder' );
/**
 * Registers the ACF field type.
 */
function boo_acf_pb_include_acf_field_page_builder() {
	if ( ! function_exists( 'acf_register_field_type' ) ) {
		return;
	}

	require_once __DIR__ . '/class-boo-acf-pb-acf-field-page-builder.php';

	acf_register_field_type( 'boo_acf_pb_acf_field_page_builder' );
}
