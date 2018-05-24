<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
<div class="single-quantity-wrap">
	<div class="single-quantity">Quantity</div>
	<?php
		/**
		 * @since 3.0.0.
		 */
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
		) );

		/**
		 * @since 3.0.0.
		 */
		do_action( 'woocommerce_after_add_to_cart_quantity' );
	?>
</div>
<div class="woocommerce-variation-add-to-cart variations_button">

	<button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />

	<div class="cardsafe-image"><img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload"  data-src="<?php echo get_template_directory_uri(); ?>/assets/images/safe_cards.png"/></div>
	<div class="garantee-info">
		<p>Frames not included. Choose your own style to match your decor.</p>
		<p>All prints are lovingly made to order and printed on heavyweight, high quality white archival paper and shipped in sturdy, protective packaging.</p>
		<p><strong>Estimated shipping times</strong> </br>
<span>North America:</span> 8-16 business days</br>
<span>Europe:</span> 5-8 business days</br>
<span>Australia, New Zealand and Oceania:</span> 10-20 business days</br>
<span>Asia Pacific:</span> 10-20 business days</br>
<span>Latin America and the Caribbean:</span> 15-25 business days</p>



	</div>
</div>
