<?php 
/**
 * The contents for Archives.
 *
 */

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$featured_image_size = ($deviceType == 'phone') ? 'small_screen': 'big_screen';
$no_sidebar = ( !roots_display_sidebar() )? 'no-sidebar':'';

?>

<article <?php post_class(); ?> >

	<header>
		<div class="wrapper l-submain <?php echo $no_sidebar; ?>" >
			<div class="sub-wrapper l-submain-h g-html " style="border:none">
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php get_template_part('templates/entry-meta'); ?>
			</div>
		</div>
	</header>
	
	<?php if ( has_post_thumbnail() ) { ?>
        <div class="entry-image" style="">
            <?php $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large', false, '' ); ?>
            <a href="<?php echo $src[0]; ?>" title="<?php the_title(); ?>" rel="lightbox">
                <?php the_post_thumbnail( $featured_image_size ); ?>
            </a>
        </div>
    <?php } ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>

</article>
