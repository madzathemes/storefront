<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version' => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce = require 'inc/woocommerce/class-storefront-woocommerce.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

add_action( 'woocommerce_before_single_product', 'move_variations_single_price', 1 );
function move_variations_single_price(){
    global $product, $post;

    if ( $product->is_type( 'variable' ) ) {
        // removing the variations price for variable products
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

        // Change location and inserting back the variations price
        add_action( 'woocommerce_single_product_summary', 'replace_variation_single_price', 10 );
    }
}

add_filter( 'woocommerce_variable_sale_price_html', 'bbloomer_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'bbloomer_variation_price_format', 10, 2 );


function bbloomer_variation_price_format( $price, $product ) {


// Main Price
$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

// Sale Price
$prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
sort( $prices );
$saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

if ( $price !== $saleprice ) {
$price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
}
return $price;
}
function replace_variation_single_price(){
    global $product;

    // Main Price
    $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
    $price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    // Sale Price
    $prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
    sort( $prices );
    $saleprice = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

    if ( $price !== $saleprice && $product->is_on_sale() ) {
        $price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
    }

    ?>
    <style>
        div.woocommerce-variation-price,
        div.woocommerce-variation-availability,
        div.hidden-variable-price {
            height: 0px !important;
            overflow:hidden;
            position:relative;
            line-height: 0px !important;
            font-size: 0% !important;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $('select').blur( function(){
            if( '' != $('input.variation_id').val() ){
                if($('p.availability'))
                    $('p.availability').remove();
                $('p.price').html($('div.woocommerce-variation-price > span.price').html()).append('<p class="availability">'+$('div.woocommerce-variation-availability').html()+'</p>');
                console.log($('input.variation_id').val());
            } else {
                $('p.price').html($('div.hidden-variable-price').html());
                if($('p.availability'))
                    $('p.availability').remove();
                console.log('NULL');
            }
        });
    });
    </script>
    <?php

    echo '<p class="price">'.$price.'</p>
    <div class="hidden-variable-price" >'.$price.'</div>';
}
/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}


function get_variation_data_from_variation_id( $item_id ) {
    $_product = new WC_Product_Variation( $item_id );
    $variation_data = $_product->get_variation_attributes();
    $variation_detail = wc_get_formatted_variation( $variation_data, true );  // this will give all variation detail in one line
    // $variation_detail = woocommerce_get_formatted_variation( $variation_data, false);  // this will give all variation detail one by one
    return $variation_detail; // $variation_detail will return string containing variation detail which can be used to print on website
    // return $variation_data; // $variation_data will return only the data which can be used to store variation data
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


 add_filter( 'woocommerce_checkout_fields' , 'my_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function my_override_checkout_fields( $fields ) {

	$fields['billing']['billing_postcode']['label'] = 'Postal Code';
  $fields['billing']['billing_postcode']['placeholder'] = 'Postal Code';

	$fields['billing']['billing_first_name']['label'] = 'First Name (optional)';
  $fields['billing']['billing_first_name']['placeholder'] = 'First Name (optional)';

	$fields['billing']['billing_last_name']['label'] = 'Last Name';
  $fields['billing']['billing_last_name']['placeholder'] = 'Last Name';

	$fields['billing']['billing_address_1']['label'] = 'Address';
  $fields['billing']['billing_address_1']['placeholder'] = 'Address';

	$fields['billing']['billing_address_2']['label'] = 'Apartment, suite, etc. (optional)';
  $fields['billing']['billing_address_2']['placeholder'] = 'Apartment, suite, etc. (optional)';

	$fields['billing']['billing_city']['label'] = 'City';
  $fields['billing']['billing_city']['placeholder'] = 'City';

	$fields['billing']['billing_state']['label'] = 'State';
  $fields['billing']['billing_state']['placeholder'] = 'State';

	$fields['billing']['billing_email']['label'] = 'Email';
	$fields['billing']['billing_email']['placeholder'] = 'Email';

	$address_fields['postcode']['label'] = 'Pin Code';

	return $fields;
}

function bbloomer_move_checkout_email_field( $address_fields ) {
    $address_fields['billing_email']['priority'] = 1;
		$address_fields['billing_country']['priority'] = 80;
		$address_fields['billing_state']['priority'] = 91;
		$address_fields['billing_postcode']['priority'] = 92;
    return $address_fields;
}

add_filter( 'woocommerce_billing_fields', 'bbloomer_move_checkout_email_field', 10, 1 );

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
#add_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );

add_filter( 'woocommerce_checkout_fields', 'bbloomer_change_autofocus_checkout_field' );

function bbloomer_change_autofocus_checkout_field( $fields ) {
$fields['billing']['billing_first_name']['autofocus'] = false;
$fields['billing']['billing_email']['autofocus'] = true;
return $fields;
}


// Change images alt and title tag
add_filter('wp_get_attachment_image_attributes', 'change_attachement_image_attributes', 20, 2);
function change_attachement_image_attributes($attr, $attachment) {
global $post;
$product = wc_get_product( $post->ID );
if ($post->post_type == 'product') {
    $title = $post->post_title;
    $authortags = strip_tags (wc_get_product_tag_list($post->ID));
    $editor = $product->get_attribute( 'pa_szerkesztette' );

    $attr['alt'] = $title .' '.$authortags .' '. $editor;
    #$attr['title'] = $authortags .' '. $editor;
    }
    return $attr;
}

function storefront_x_sidebar_widget_init() {

		register_sidebar( array(
			'name' => esc_html__( 'Smart Menu Widgets', 'anews'),
			'id' => 'smart-menu-widget-area',
			'description' => esc_html__( 'The Smart Menu Widget area' , 'anews'),
			'before_widget' => '<div class="widget">',
			'after_widget' => '<div class="clear"></div></div>',
					'before_title' => '<h2 class="heading"><span>',
					'after_title' => '</span></h4>',
		) );

}

add_action( 'widgets_init', 'storefront_x_sidebar_widget_init' );


add_action( 'init', 'custom_taxonomy_Item' );
function custom_taxonomy_Item()  {
$labels = array(
    'name'                       => 'Sections',
    'singular_name'              => 'Section',
    'menu_name'                  => 'Section',
    'all_items'                  => 'All Items',
    'parent_item'                => 'Parent Section',
    'parent_item_colon'          => 'Parent Section:',
    'new_item_name'              => 'New Section Name',
    'add_new_item'               => 'Add New Section',
    'edit_item'                  => 'Edit Section',
    'update_item'                => 'Update Section',
    'separate_items_with_commas' => 'Separate Section with commas',
    'search_items'               => 'Search Sections',
    'add_or_remove_items'        => 'Add or remove Sections',
    'choose_from_most_used'      => 'Choose from the most used Sections',
);
$args = array(
    'labels'                     => $labels,
    'hierarchical'               => true,
    'public'                     => true,
    'show_ui'                    => true,
    'show_admin_column'          => true,
    'show_in_nav_menus'          => true,
    'show_tagcloud'              => true,
);
register_taxonomy( 'sections', 'product', $args );
register_taxonomy_for_object_type( 'sections', 'product' );
}

/**
 * This code should be added to functions.php of your theme
 **/
add_filter('woocommerce_default_catalog_orderby', 'custom_default_catalog_orderby');

function custom_default_catalog_orderby() {
     return 'date'; // Can also use title and price
}
