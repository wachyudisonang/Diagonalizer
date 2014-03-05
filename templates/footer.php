<?php
/**
 * The contents for displaying Footer.
 *
 */

include FUNCTIONS_DIR . 'footer_functions.php';

?>

<footer id="footers" class="content-info" role="contentinfo">
	<div class="wrapper top">
		<div class="sub-wrapper">
			<div class="row">
				<?php diagonalizer_footer(); ?>
			</div>
		</div>
	</div>
	<div class="wrapper bottom">
		<div class="sub-wrapper">
			<p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
			<br /><div class="redux-timer"><?php echo get_num_queries(); ?> queries in <?php echo timer_stop(0); ?> seconds</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
