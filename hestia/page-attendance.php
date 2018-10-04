<?php
/**
 * The WBC Youth Directory Single Youth Page.
 *
 * Warning: This page will not work without the correct custom post type
 * and a lot of customized Advanced Custom Fields.
 */

// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';

// Default Hestia information
get_header();
do_action('hestia_before_single_post_wrapper');
$default = hestia_get_blog_layout_default();
$sidebar_layout = apply_filters('hestia_sidebar_layout', get_theme_mod('hestia_blog_sidebar_layout', $default));
$wrap_class = apply_filters('hestia_filter_single_post_content_classes', 'col-md-8 single-post-container');

$classifications = get_youth_directory_classifications();
?>

<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <?php
            if (!is_user_logged_in()) :
                get_template_part('youth-directory/login-override');
            else :
                ?>
                <article id="post-<?php the_ID(); ?>" class="section section-text">
                    <div class="row">
                        <div class="single-post-wrap youth-directory-wrap">
                            <form action="<?php bloginfo('url'); ?>/submit-attendance/" method="get" style="margin:0px 15px;font-size:1.25em;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="date">Date</label>
                                        <input type="text" name="date" class="form-control" id="date" value="<?php echo date('m/d/Y'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="event">Event</label><br/>
                                        <select class="event" name="event" id="event" style="width:100%;">
                                            <option selected>Choose One</option>
                                            <option value="sunday_school">Sunday School</option>
                                            <option value="sunday_night">Sunday Night</option>
                                            <option value="wednesday_night">Wednesday Night</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <?php foreach ($classifications as $classification => $users) : ?>
                                            <div class="row" style="margin-bottom:15px;">
                                                <p style="border-bottom:1px solid #999; margin-bottom:15px;"><?php echo $classification; ?></p>
                                                <?php foreach ($users as $user) : ?>
                                                    <?php $unique_name = 'attendance_' . $user['id']; ?>
                                                    <div class="col-md-3" style="padding:5px;">
                                                        <input class="form-check-input" name="attendance[]" type="checkbox" value="<?php echo $user['id']; ?>" id="<?php echo $unique_name; ?>">
                                                        <label class="form-check-label" for="<?php echo $unique_name; ?>">&nbsp;&nbsp;&nbsp;<?php echo $user['title']; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom:15px;">
                                    <div class="col-md-12">
                                        <button class="btn btn-info" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
