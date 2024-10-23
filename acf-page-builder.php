<?php
/*
Plugin Name: ACF Page Builder
Plugin URI:  https://boost.dev
Description: Enhancements to Flexible Content
Version:     0.0.1
Author:      boost.dev
Author URI:  https://boost.dev
License:     MIT
License URI: https://opensource.org/licenses/MIT
*/

if ( ! defined( 'ABSPATH' ) ) exit;


// require( __DIR__ . '/acf-field/init.php' );

// return;

add_action( 'admin_enqueue_scripts', 'boo_acf_pb_admin_enqueue_scripts' );
function boo_acf_pb_admin_enqueue_scripts( $hook_suffix ) {
  wp_enqueue_script('jquery');
  wp_enqueue_style( 'boo_acf_pb_styles', plugins_url('css/boo_acf_pb.css', __FILE__ ), array(), '1.0.0', false);
  wp_enqueue_script( 'boo_acf_pb_scripts', plugins_url('js/boo_acf_pb.js', __FILE__ ), array('jquery', 'acf-input'), '1.0.0', false);

  // localise the script with the plugin dir
  wp_localize_script( 'boo_acf_pb_scripts', 'boo_acf_pb', array(
    'plugin_url' => plugin_dir_url( __FILE__ )
  ));

}

add_action('acf/render_field_presentation_settings/type=flexible_content', 'boo_acf_pb_settings');
function boo_acf_pb_settings( $field ) {

  acf_render_field_setting( $field, array(
    'label'         => __('Page Builder'),
    'instructions'  => 'Turn on and select repeater tab orientation',
    'name'          => 'boo-acf-pb-orientation',
    'type'          => 'radio',
    'layout'        => 'horizontal',
    'choices'       => array(
      false => __('Off'),
      'vertical' => __('Vertical'),
      'horizontal' => __('Horizontal')
    )
  ), true);

}

add_filter('acf/render_field/type=flexible_content', 'boo_acf_pb_render_pre', 9, 1);
function boo_acf_pb_render_pre( $field ) {
	  echo '<div class="boo-acf-pb-activated"> ';
}

add_filter('acf/render_field/type=flexible_content', 'boo_acf_pb_render_post', 11, 1);
function boo_acf_pb_render_post( $field ) {

    echo '<div class="boo-acf-pb-layout-picker">';
    echo '<div class="boo-acf-pb-layout-picker-buttons">';
    foreach ($field["layouts"] as $layout) {
      echo '<button class="flexible-content-test" data-layout="' . $layout["name"] . '">';
      echo '<strong>' . $layout["name"] . '</strong>';
      echo '</button>';
    }
    echo '</div>';
    echo '<div class="boo-acf-pb-layout-picker-preview">';
     foreach ($field["layouts"] as $layout) {
      $img_src = plugin_dir_url( __FILE__ ) . "/layouts/" . $layout["name"] . ".jpg";
      echo '<img src="'.$img_src.'" data-layout="' . $layout["name"] . '"/>';

    }
    echo '</div>';
    echo '</div>';
    echo '</div>';


}



/** ------------ sub options page ------------ **/
add_action('acf/init', 'acf_page_builder_options_page', 105);
function acf_page_builder_options_page() {
  if( function_exists('acf_add_options_sub_page') ) {


    acf_add_options_sub_page(array(
      'page_title'  => 'Page Builder',
      'menu_title'  => 'Page Builder',
      'parent_slug' => 'edit.php?post_type=acf-field-group',
      // 'parent_slug' => 'more-content',
      'capability'  => 'edit_posts',
    ));
  }

}
