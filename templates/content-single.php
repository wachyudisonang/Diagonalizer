<?php 
/**
 * The contents for a Post [single].
 *
 */

$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$featured_image_size = ($deviceType == 'phone') ? 'small_screen': 'big_screen';

?>

<?php while (have_posts()) : the_post(); ?>

    <article <?php post_class(); ?>>

        <!-- <header>
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php //get_template_part('templates/entry-meta'); ?>
        </header> -->

        <?php get_template_part('templates/entry-meta'); ?>

        <?php if ( has_post_thumbnail() ) { ?>
        <div class="entry-image" style="">
            <?php $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large', false, '' ); ?>
            <a href="<?php echo $src[0]; ?>" title="<?php the_title(); ?>" rel="lightbox">
                <?php the_post_thumbnail( $featured_image_size ); ?>
            </a>
        </div>
        <?php } ?>
        
        <div class="entry-content">
            <?php the_content(); ?>
        </div>

        <footer>
            <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
        </footer>

        <?php comments_template('/templates/comments.php'); ?>

    </article>



<?php endwhile; ?>
