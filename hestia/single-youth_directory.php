<?php
/**
 * The WBC Youth Directory Single Youth Page.
 *
 * Warning: This page will not work without the correct custom post type
 * and a lot of customized Advanced Custom Fields.
 */

// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';
remove_action( 'hestia_do_header', 'hestia_the_header_content' ); // another comment

// Default Hestia information
get_header();
do_action('hestia_before_single_post_wrapper');
$default        = hestia_get_blog_layout_default();
$sidebar_layout = apply_filters( 'hestia_sidebar_layout', get_theme_mod( 'hestia_blog_sidebar_layout', $default ) );
$wrap_class     = apply_filters( 'hestia_filter_single_post_content_classes', 'col-md-8 single-post-container' );

// Inject my styles into the page
get_template_part('youth-directory/styles');

// Gathering all the custom Youth Directory information
$first_name = get_field('youth_member_first_name');
$last_name = get_field('youth_member_last_name');
$shirt_size = get_field('shirt_size');
$birthday = get_field('youth_member_birthday');
$grade = get_field('youth_member_current_grade');
$school = get_field('youth_member_school');
$address = get_field('youth_member_address');
$emergency_contact_name = get_field('youth_member_emergency_contact_name');
$emergency_contact_number = get_field('youth_member_emergency_contact_number');
$insurance_information = get_field('youth_member_insurance_information');

// Fun Information
$ice_cream = get_field('fun_favorite_ice_cream_flavor');
$pizza = get_field('fun_favorite_pizza');
$candy = get_field('fun_favorite_candy');
$current_team = get_field('fun_current_team');

// Spiritual Information
$saved = get_field('has_been_saved');
$baptized = get_field('has_been_baptized');
$missions = get_field('has_been_mission_trip');

// Set display information
$tags = get_the_tags();
$tag_count = count($tags);
if (isset($emergency_contact_name) && trim($emergency_contact_name) !== '') {
    $emergency_contact_name_display = $emergency_contact_name;
} else {
    $emergency_contact_name_display = '<span class="text-danger">Emergency Contact Not Set</span>';
}
if (isset($emergency_contact_number) && trim($emergency_contact_number) !== '') {
    $emergency_contact_number_display = $emergency_contact_number;
} else {
    $emergency_contact_number_display = '<span class="text-danger">Emergency Contact Not Set</span>';
}

// Gather attendance information
$all_time_attendance = get_youth_directory_attendance(get_the_ID(), 999);
$three_month_attendance = get_youth_directory_attendance(get_the_ID(), 3);
$last_month_attendance = get_youth_directory_attendance(get_the_ID());
?>

<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <?php
            if (!is_user_logged_in()) {
                get_template_part('youth-directory/login-override');
            } else {
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" class="section section-text">
                            <div class="row">
                                <div class="single-post-wrap youth-directory-wrap">
                                    <div class="row">
                                        <h4 style="text-align:center;">Designations &amp; Attendance</h4>
                                        <div class="col-md-12 tag-list" style="margin-bottom:20px;">
                                            <p>
                                                <?php
                                                $i = 1;
                                                foreach ($tags as $tag) :
                                                    ?>
                                                    <span class="individual-tag"><?php echo $tag->name; ?></span><?php if ($i < $tag_count) : ?>|<?php endif; ?>
                                                    <?php
                                                    ++$i;
                                                endforeach;
                                                ?>
                                            </p>
                                        </div>

                                        <div class="col-md-4">
                                            <p class="youth-directory-attendance">
                                                <strong>All Time Attendance:</strong><br/>
                                                <?php echo display_youth_directory_attendance($all_time_attendance); ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="youth-directory-attendance">
                                                <strong>Last Three Month's Attendance:</strong><br/>
                                                <?php echo display_youth_directory_attendance($three_month_attendance); ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="youth-directory-attendance">
                                                <strong>Last Month's Attendance:</strong><br/>
                                                <?php echo display_youth_directory_attendance($last_month_attendance); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <hr/>

                                    <h3>Basic Information</h3>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>Name:</strong> <?php echo $first_name . ' ' . $last_name; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Shirt Size:</strong> <?php echo $shirt_size; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Birthday:</strong> <?php echo $birthday; ?></p>
                                        </div>
                                        <div clas="col-md-3">
                                            <p><strong>Grade:</strong> <?php echo $grade; ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p><strong>School:</strong> <?php echo $school; ?></p>
                                        </div>
                                        <div class="col-md-9">
                                            <p><strong>Team:</strong> <?php echo $current_team; ?></p>
                                        </div>
                                    </div>

                                    <h3>Fun Information</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Favorite Pizza:</strong> <?php echo $pizza; ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Favorite Ice Cream:</strong> <?php echo $ice_cream; ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Favorite Candy:</strong> <?php echo $candy; ?></p>
                                        </div>
                                    </div>

                                    <h3>Spiritual Information</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Saved:</strong> <?php echo ($saved) ? 'Yes' : 'No'; ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Baptized:</strong> <?php echo ($baptized) ? 'Yes' : 'No'; ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Mission Trip:</strong> <?php echo ($missions) ? 'Yes' : 'No'; ?></p>
                                        </div>
                                    </div>

                                    <h3>Emergency Information</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Emergency Contact:</strong> <?php echo $emergency_contact_name_display; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Emergency Contact Number:</strong> <?php echo $emergency_contact_number_display; ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Address:</strong><br/><?php echo $address; ?></p>
                                        </div>
                                    </div>
                                    <?php if (is_admin()) : ?>
                                        <div class="col-md-12">
                                            <p><strong>Insurance Information:</strong> <?php echo_pre($insurance_information); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                        </article>
                        <?php
                    endwhile;
                else :
                    get_template_part('template-parts/content', 'none');
                endif;
            }
            ?>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
