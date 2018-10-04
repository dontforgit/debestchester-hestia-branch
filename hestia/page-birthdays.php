<?php
// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';

// One month from now
$today = date('Y-m-d');
$next_month = date('Y-m-d', strtotime('+1month'));

// Default Hestia information
get_header();
do_action('hestia_before_single_post_wrapper');
$default = hestia_get_blog_layout_default();
$sidebar_layout = apply_filters('hestia_sidebar_layout', get_theme_mod('hestia_blog_sidebar_layout', $default));
$wrap_class = apply_filters('hestia_filter_single_post_content_classes', 'col-md-8 single-post-container');
?>

<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <article id="post-<?php the_ID(); ?>" class="section section-text">
                <div class="row">
                    <div class="single-post-wrap youth-directory-wrap">
                        <?php
                        // Find the birthdays within the next month
                        $args = array(
                            'posts_per_page' => -1,
                            'post_type' => 'youth_directory',
                            'meta_query'	=> array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'youth_member_birthday',
                                    'value' => $today,
                                    'compare' => '>'
                                ),
                                array(
                                    'key' => 'youth_member_birthday',
                                    'value' => $next_month,
                                    'compare' => '<'
                                ),
                            ),
                        );

                        // query
                        $the_query = new WP_Query( $args );

                        ?>
                        <?php if( $the_query->have_posts() ): ?>
                        <ul>
                            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php the_field('event_thumbnail'); ?>" />
                                        <?php the_title(); ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php endif; ?>

                        <?php wp_reset_query(); ?>

                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
