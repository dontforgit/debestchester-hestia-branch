<?php
get_header();
do_action('hestia_before_single_post_wrapper');
?>

<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <?php
            if (is_user_logged_in()) {

                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/content', 'single');
                    endwhile;
                else :
                    get_template_part('template-parts/content', 'none');
                endif;
            } else {
                ?>
                <style>
                    /* Hack to hide student name before login */
                    h1.hestia-title.entry-title{
                        visibility:hidden;
                    }
                    h1.hestia-title.entry-title:after{
                        visibility:visible;
                        display:block;
                        content: "Protected: Login to view this page.";
                    }
                    .author{
                        display:none;
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
<div class="footer-wrapper">
    <?php get_footer(); ?>
