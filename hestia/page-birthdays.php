<?php
// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';
require_once dirname(__FILE__ ) . '/youth-directory/eventAttendance.php';

$this_month = date('m');
$this_year = date('Y');

// Get birthday information if exists
$sql = "SELECT pm.meta_value AS birthday, p.post_title AS youth, p.post_name AS slug
        FROM wp_postmeta pm 
          LEFT JOIN wp_posts p ON pm.post_id = p.ID
        WHERE pm.meta_key = 'youth_member_birthday' AND pm.meta_value <> ''
        ORDER BY pm.meta_value ASC";
$birthdays = $wpdb->get_results($sql);

$unsorted_birthdays = array();
foreach ($birthdays as $birthday) {
    $birthday_month = date('m', strtotime($birthday->birthday));
    $birthday_day = date('d', strtotime($birthday->birthday));
    $birthday_year = date('Y', strtotime($birthday->birthday));
    $data = array(
        'birthday_date' => date('m/d/Y', strtotime($birthday->birthday)),
        'birthday_year' => $birthday_year,
        'birthday_month' => $birthday_month,
        'name' => $birthday->youth,
    );
    $unsorted_birthdays[$birthday_month . '-' . $birthday_day . '-' . $birthday->slug] = $data;
}

ksort($unsorted_birthdays);

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
                        <?php foreach ($unsorted_birthdays as $this_birthday) : ?>
                        <div class="col-md-3">
                            <?php
                            $class = ($this_birthday['birthday_month'] == $this_month) ? 'text-danger' : '';
                            $turning = ($this_birthday['birthday_month'] == $this_month) ? ($this_year - $this_birthday['birthday_year']) : '';
                            ?>
                            <p class="<?php echo $class; ?>">
                                <strong><?php echo $this_birthday['name']; ?></strong>
                                <?php if (trim($turning) !== '') : ?>
                                    (<?php echo $turning; ?>)
                                <?php endif; ?>
                                <br/>
                                <?php echo $this_birthday['birthday_date']; ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
