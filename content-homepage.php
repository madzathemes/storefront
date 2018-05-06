<?php
/**
 * The template used for displaying page content in template-homepage.php
 *
 * @package storefront
 */

?>
<?php
$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
?>
<div class="liamhome-slider">
<?php echo do_shortcode('[rev_slider alias="product-showcase"]'); ?>
<?php echo do_shortcode('[woocommerce_recently_viewed_products]'); ?>
</div>
