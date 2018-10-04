<?php
// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';

// Make sure that we have what we need before we try to write to attendance table
if (!isset($_GET['attendance']) || $_GET['event'] === 'Choose One') {
    die;
}

// Setting the stage
global $wpdb;
$table_name = $wpdb->prefix . 'attendance';
$attendees = $_GET['attendance'];
$date = $_GET['date'];
$event = $_GET['event'];
$attendee_total = count($attendees);

// Writing attendance to the database
foreach ($attendees as $attendee) {
    $data = array(
        'attend_date' => $date,
        'attend_event' => $event,
        'attend_id' => $attendee
    );
    $wpdb->insert($table_name, $data);
}

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
                            <p>Today's attendance: <?php echo $attendee_total; ?></p>
                        </div>
                    </div>
                </article>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
