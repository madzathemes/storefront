<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
<script>
jQuery(document).ready( function( $ ) {
	jQuery(document).on( 'mouseenter', '.wmc-currency-wrapper', function() {
		jQuery(document).trigger('customlazyloadxtevent');
	});

jQuery('.form-row > input').on('keyup', function() {
	if (jQuery(this).val() != '') {
    jQuery(this).closest(".woocommerce-billing-fields .form-row").addClass('value-exists');
  }
	if (jQuery(this).val() == '') {
    jQuery(this).closest(".woocommerce-billing-fields .form-row").removeClass('value-exists');
  }
});
jQuery('.form-row > input').each(function(){
	if (jQuery(this).val() != '') {
    jQuery(this).closest(".woocommerce-billing-fields .form-row").addClass('value-exists');
  }
	if (jQuery(this).val() == '') {
    jQuery(this).closest(".woocommerce-billing-fields .form-row").removeClass('value-exists');
  }
});

jQuery('.form-row.validate-state > input').on('keyup', function() {
	if (jQuery(this).val() != '') {
    jQuery(this).closest(".woocommerce-billing-fields .form-row").addClass('value-exists');
  }
	if (jQuery(this).val() == '') {
    jQuery(this).closest(".woocommerce-billing-fields .form-row").removeClass('value-exists');
  }
});

jQuery('.form-row .select2').each(function(){
    jQuery(this).closest(".form-row").addClass('value-select');
});



	jQuery('.form-row.validate-email').append('<h3 class="shipingadress">Shipping address</h3>');

});

</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118664792-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118664792-1');
</script>
</body>
</html>
