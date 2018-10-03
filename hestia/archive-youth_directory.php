<?php
/**
 * The WBC Youth Directory Listing Page.
 *
 * Warning: This page will not work without the correct custom post type.
 */

// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';

// Default Hestia information
get_header();
$default = hestia_get_blog_layout_default();
$sidebar_layout = apply_filters('hestia_sidebar_layout', get_theme_mod('hestia_blog_sidebar_layout', $default));
$wrapper_classes = apply_filters('hestia_filter_archive_content_classes', 'col-md-8 archive-post-wrap');
do_action( 'hestia_before_archive_content' );

// Customized Youth Directory Query
$args = array(
    'post_type' => 'youth_directory',
    'posts_per_page' => -1,
    'orderby' => array(
        'title' => 'ASC'
    ),
);
$directory = new WP_Query($args);
?>
<div class="<?php echo hestia_layout(); ?>">
    <div class="hestia-blogs" data-layout="<?php echo esc_attr( $sidebar_layout ); ?>">
        <div class="container">
            <div class="row">
                <?php
                if (!is_user_logged_in()) :
                    get_template_part('youth-directory/login-override');
                else :
                    while ($directory->have_posts()) :
                        $directory->the_post();
                        ?>
                        <div class="col-md-4">
                            <h3><a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php get_footer(); ?>
<?php
