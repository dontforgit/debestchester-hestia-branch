<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package Hestia
 * @since Hestia 1.0
 * @modified 1.1.30
 */

get_header();

$default         = hestia_get_blog_layout_default();
$sidebar_layout  = apply_filters( 'hestia_sidebar_layout', get_theme_mod( 'hestia_blog_sidebar_layout', $default ) );
$wrapper_classes = apply_filters( 'hestia_filter_archive_content_classes', 'col-md-8 archive-post-wrap' );

do_action( 'hestia_before_archive_content' );

?>

<div class="<?php echo hestia_layout(); ?>">
    <div class="hestia-blogs" data-layout="<?php echo esc_attr( $sidebar_layout ); ?>">
        <div class="container">
            <div class="row">
                <?php
                if (is_user_logged_in()) {
                    if ( $sidebar_layout === 'sidebar-left' ) {
                        get_sidebar();
                    }
                    ?>
                    <div class="<?php echo esc_attr( $wrapper_classes ); ?>">
                        <?php
                        if ( have_posts() ) :
                            while ( have_posts() ) :
                                the_post();
                                get_template_part( 'template-parts/content' );
                            endwhile;
                            the_posts_pagination();
                        else :
                            get_template_part( 'template-parts/content', 'none' );
                        endif;
                        ?>
                    </div>
                    <?php

                    if ( $sidebar_layout === 'sidebar-right' ) {
                        get_sidebar();
                    }
                } else {
                    ?>
                    <style>
                        /* Hack to hide student name before login */
                        h1.hestia-title{
                            visibility:hidden;
                        }
                        h1.hestia-title:after{
                            visibility:visible;
                            display:block;
                            content: "Protected: Login to view this page.";
                        }
                    </style>
                    <article id="post-<?php the_ID(); ?>" class="section section-text">
                        <div class="row">
                            <div class="<?php echo esc_attr( $wrap_class ); ?>">
                                <?php do_action( 'hestia_before_page_content' ); ?>
                                <h2>You must be logged in to view this page.</h2>
                                <?php wp_login_form(); ?>
                            </div>
                        </div>
                    </article>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <?php
    get_footer(); ?>
