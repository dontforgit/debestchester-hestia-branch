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

$classifications = get_youth_directory_classifications();
?>
<div class="<?php echo hestia_layout(); ?>">
    <div class="hestia-blogs" data-layout="<?php echo esc_attr( $sidebar_layout ); ?>">
        <div class="container">
            <?php
            if (!is_user_logged_in()) :
                get_template_part('youth-directory/login-override');
            else :
                foreach ($classifications as $classification => $users) : ?>
                    <div class="row">
                        <h3 style="border-bottom:1px solid #999; margin-bottom:15px;"><?php echo $classification; ?></h3>
                        <?php foreach ($users as $user) : ?>
                            <div class="col-md-3">
                                <p style="font-size:1.25em;">
                                    <a href="<?php echo $user['link']; ?>" style="color:#999; text-decoration: underline;">
                                        <?php echo $user['title']; ?>
                                    </a>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php get_footer();
