<?php
/*
Plugin Name: ACF Page Builder
Plugin URI:  https://boost.dev
Description: Enhancements to Flexible Content
Version:     0.0.2
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
  // only render for superadmins

    echo "<style>
    .acf-fc-layout-controls .-plus {
      display: none !important;
    }
    </style>";




    ?>
<div>
  <p>Presets</p>
  <button data-preset="post_grid--masonry,post_grid--minimal,post_grid--excerpt,faqs">Test</button>
  <button
    data-preset="hero,services_grid,testimonials_carousel,case_studies_accordion,text_feature,post_grid--masonry,faqs">Home</button>
  <button
    data-preset="page_intro,service_tabs,callout,text_carousel,two_column_text_carousel,related_case_studies,faqs">Service</button>
  <button data-preset="featured_case_study,post_grid">Case Study Landing</button>
  <button data-preset="page_intro,two_column_text,quote,timeline,testimonials_carousel,featured_article,post_grid">About
    Us</button>
  <button data-preset="page_intro,post_grid">Blog Landing</button>
  <button data-preset="contact_us">Contact Us</button>
</div>
<?php




    // modal
    ?>
<button class="button button-primary" data-layout-picker-open>Add Layout</button>
<div class='boo-acf-pb-layout-picker-container'>
  <div class='boo-acf-pb-layout-picker-modal'>
    <div class='header'>
      <p class="boo-acf-pb-layout-header-text">Select a layout</p>
      <p class="boo-acf-pb-layout-tip">Tip: Press &lt;Enter&gt; to add. &lt;Ctrl&gt; + &lt;Enter&gt; to keep going</p>
    </div>
    <div class='layout-picker-grid-container'>
      <div class='layout-picker-grid'>
        <div class="layout-picker-sidebar">
          <div class='layout-picker-search'>
            <input type="text" data-layout-picker-search placeholder="Search..." />
          </div>

          <div class='layout-picker-items-scrollable'>
            <div class='layout-picker-items'>
              <?php $i = 0;
          foreach ($field["layouts"] as $layout) : ?>
              <button class='layout-picker-item <?php if ($i == 0) echo "selected"; $i++ ?>'
                data-layout='<?php echo $layout['name']; ?>'>
                <?php echo $layout['label']; ?>
              </button>

              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class='layout-picker-previews'>
          <div class='layout-picker-preview-container'>
            <div class='layout-picker-preview-scrollable'>
              <!-- <p class="preview-text">preview</p> -->
              <?php
        $i = 0;
        foreach ($field["layouts"] as $layout) : ?>
              <img class="<?php if ($i == 0) echo "selected"; $i++ ?>"
                src='<?php echo plugin_dir_url( __FILE__ ) . "/layouts/" . $layout["name"] . ".jpg"; ?>'
                data-layout='<?php echo $layout['name']; ?>' />
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class='footer'>
      <button class='button button-secondary' data-expand-sidebar>
        <svg id="fi_17819458" enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <g>
            <path
              d="m16 22.8h-8c-3.7 0-6.8-3-6.8-6.8v-8c0-3.7 3-6.8 6.8-6.8h8c3.7 0 6.8 3 6.8 6.8v8c0 3.7-3.1 6.8-6.8 6.8zm-8-20c-2.9 0-5.2 2.3-5.2 5.2v8c0 2.9 2.4 5.3 5.3 5.3h8c2.9 0 5.3-2.4 5.3-5.3v-8c0-2.9-2.4-5.3-5.3-5.3h-8.1z">
            </path>
          </g>
          <g>
            <path
              d="m17 11.8c-.4 0-.8-.3-.8-.8v-3.2h-3.2c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h3.5c.7 0 1.3.6 1.3 1.3v3.5c0 .4-.4.8-.8.8z">
            </path>
          </g>
          <g>
            <path
              d="m13 11.8c-.2 0-.4-.1-.5-.2-.3-.3-.3-.8 0-1.1l3.5-3.5c.3-.3.8-.3 1.1 0s.3.8 0 1.1l-3.5 3.5c-.2.1-.4.2-.6.2z">
            </path>
          </g>
          <g>
            <path d="m11 17.8h-3.5c-.7 0-1.3-.6-1.3-1.3v-3.5c0-.4.3-.8.8-.8s.8.3.8.8v3.3h3.2c.4 0 .8.3.8.8s-.4.7-.8.7z">
            </path>
          </g>
          <g>
            <path
              d="m7.5 17.3c-.2 0-.4-.1-.5-.2-.3-.3-.3-.8 0-1.1l3.5-3.5c.3-.3.8-.3 1.1 0s.3.8 0 1.1l-3.6 3.4c-.1.2-.3.3-.5.3z">
            </path>
          </g>
        </svg>
        <span>Expand sidebar</span>
      </button>
      <button class='button button-primary' data-add-layout>Add Layout</button>
    </div>
  </div>
</div>
<?php
  echo '</div>';

}



/** ------------ sub options page ------------ **/
// to configure images and tagging once we dont hard code them
// add_action('acf/init', 'acf_page_builder_options_page', 105);
// function acf_page_builder_options_page() {
//   if( function_exists('acf_add_options_sub_page') ) {


//     acf_add_options_sub_page(array(
//       'page_title'  => 'Page Builder',
//       'menu_title'  => 'Page Builder',
//       'parent_slug' => 'edit.php?post_type=acf-field-group',
//       // 'parent_slug' => 'more-content',
//       'capability'  => 'do_superadmin_stuff',
//     ));
//   }
// }
