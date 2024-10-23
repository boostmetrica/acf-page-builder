<?php
/**
 * Defines the custom field type class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * boo_acf_pb_acf_field_page_builder class.
 */
class boo_acf_pb_acf_field_page_builder extends acf_field_flexible_content {
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct() {



		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("page_builder", "error");
		 * ```
		 */
		$this->l10n = array(
			'error'	=> __( 'Error! Please enter a higher value', 'acf-page-builder' ),
		);

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-page-builder directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		$this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		parent::__construct();
	}

		public function initialize() {
      // call parent class to initialize
      parent::initialize();

			// vars
			$this->name          = 'page_builder';
			$this->label         = __( 'Page Builder', 'acf-page-builder' );
			$this->category      = 'layout';
			$this->description   = __( 'An advanced Flexible Content editor for your whole site', 'acf-page-builder' );
			$this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-flexible-content.png';
			$this->doc_url       = "https://boost.dev";
			$this->tutorial_url  = "https://boost.dev";
			$this->pro           = false;
			$this->supports      = array(
        'bindings' => false
      );
			$this->defaults      = array(
				'layouts'      => array(),
				'min'          => '',
				'max'          => '',
				'button_label' => __( 'Add Layout', 'acf' ),
			);
    }

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
	// public function render_field_settings( $field ) {
	// 	/*
	// 	 * Repeat for each setting you wish to display for this field type.
	// 	 */
	// 	acf_render_field_setting(
	// 		$field,
	// 		array(
	// 			'label'			=> __( 'Font Size','acf-page-builder' ),
	// 			'instructions'	=> __( 'Customise the input font size','acf-page-builder' ),
	// 			'type'			=> 'number',
	// 			'name'			=> 'font_size',
	// 			'append'		=> 'px',
	// 		)
	// 	);

	// 	// To render field settings on other tabs in ACF 6.0+:
	// 	// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting
	// }

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
// 	public function render_field( $field ) {
// 		// Debug output to show what field data is available.
// 		echo '<pre>';
// 		print_r( $field );
// 		echo '</pre>';

// 		// Display an input field that uses the 'font_size' setting.
// 	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	// public function input_admin_enqueue_scripts() {
	// 	$url     = trailingslashit( $this->env['url'] );
	// 	$version = $this->env['version'];

	// 	wp_register_script(
	// 		'boo_acf_pb-page-builder',
	// 		"{$url}assets/js/field.js",
	// 		array( 'acf-input' ),
	// 		$version
	// 	);

	// 	wp_register_style(
	// 		'boo_acf_pb-page-builder',
	// 		"{$url}assets/css/field.css",
	// 		array( 'acf-input' ),
	// 		$version
	// 	);

	// 	wp_enqueue_script( 'boo_acf_pb-page-builder' );
	// 	wp_enqueue_style( 'boo_acf_pb-page-builder' );
	// }
}
