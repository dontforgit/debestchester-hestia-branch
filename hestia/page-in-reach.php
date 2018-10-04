<?php
// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';

global $wpdb;
$month_offset =  (isset($_GET['months'])) ? $_GET['months'] : 1;
$one_month_ago = date('Y-m-d', strtotime('-' . $month_offset . 'month'));

// Get everyone who has attended.
$sql = "SELECT p.post_title AS attendee, COUNT(a.attend_id) AS attendance_count, a.attend_id AS user_id 
        FROM wp_attendance a 
          LEFT JOIN wp_posts p ON a.attend_id = p.ID
        WHERE a.attend_date > '{$one_month_ago}'
        GROUP BY a.attend_id;";
$attendance_results = $wpdb->get_results($sql);

// Build attendance array for easy use
$attendance = array();
foreach($attendance_results as $result) {
    $attendance[$result->user_id] = $result->attendance_count;
}

// Get all the youth
$sql = "SELECT * 
        FROM wp_posts p 
        WHERE p.post_type = 'youth_directory' 
          AND p.post_status = 'publish' 
        ORDER BY p.post_title ASC;";
$directory_results = $wpdb->get_results($sql);

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
                <h3 style="text-align:center;">Attendance from the last <?php echo $month_offset; ?> month(s)</h3>
                <div class="row">
                    <?php
                    if (!is_user_logged_in()) :
                        get_template_part('youth-directory/login-override');
                    else :
                        foreach ($directory_results as $attendee) :
                            if (isset($attendance[$attendee->ID])) {
                                $attendance_class = '';
                                $attendance_number = $attendance[$attendee->ID];
                            } else {
                                $attendance_class = 'text-danger';
                                $attendance_number = 0;
                            }
                            ?>
                            <div class="col-md-4">
                                <p class="<?php echo $attendance_class; ?>">
                                    <a href="<?php echo $attendee->guid; ?>" style="color:#999; border-bottom:1px dotted #999;">
                                        <strong><?php echo $attendee->post_title; ?></strong>
                                    </a>
                                    - <?php echo $attendance_number; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
