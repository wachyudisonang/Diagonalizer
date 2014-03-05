<?php 
/**
 * The contents for displaying Title page or post above main content.
 *
 */

include FUNCTIONS_DIR . 'page-header_functions.php';
$no_sidebar = ( !roots_display_sidebar() )?' l-submain-h g-html':'';

?>

<?php diagonalizer_homeImage(); ?><!-- /.sidebar // static or slides -->

<div class="page-header wrapper">
	<div class="sub-wrapper<?php echo $no_sidebar; ?>">
		<h1>
			<?php echo roots_title(); ?>
		</h1>
	</div>
</div>


