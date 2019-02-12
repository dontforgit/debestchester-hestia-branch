<?php
/**
 * The WBC Youth Big Event Page.
 *
 * Warning: This page will not work without the correct custom post type
 * and a lot of customized Advanced Custom Fields Pro.
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

// Keep track of all the post information
$shirt_size = array(
    'XS' => array(),
    'S' => array(),
    'M' => array(),
    'L' => array(),
    'XL' => array(),
    'XXL' => array(),
    'None' => array(),
);

$breakdown = array(
    'Student' => array(
        'Middle' => array(),
        'High' => array(),
    ),
    'College' => array(),
    'Chaperone' => array(),
);

?>
<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <?php
                if (have_posts()) :
                    while (have_posts()) :

                        // Display what is important about the event.
                        the_post();
                        the_content();

                        // This is probably where we need to check to see if the user is logged in.
                        if (is_user_logged_in()) :

                            // Build the attendance information
                            if (have_rows('attendees')) {

                                $attendee_html = youth_build_table_head(array('Name', 'Shirt Size', 'Grade', 'Form'));

                                while (have_rows('attendees')) {
                                    the_row();

                                    // Basic Information
                                    $user_id = get_sub_field('attendee');
                                    $form_required = get_field('attendancepermission_form');
                                    $attendee_name = get_the_title($user_id);

                                    // Permission Form for students, background check for volunteers
                                    if (!has_tag('Volunteer Team', $user_id)) {
                                        $permission_form = get_field($form_required, $user_id);
                                        $attendee_has_permission_form = youth_has_value($permission_form);
                                    } else {
                                        $background_check = get_field('volunteerbackground_check', $user_id);
                                        $attendee_has_permission_form = youth_has_value($background_check);
                                    }
                                    $permission_icon = $attendee_has_permission_form ? 'dashicons-yes' : 'dashicons-no';
                                    $permission_class = $attendee_has_permission_form ? '' : 'bg-danger';

                                    // Shirt Size
                                    $attendee_shirt_size = get_field('shirt_size', $user_id);
                                    $shirt_size[$attendee_shirt_size][] = $attendee_name;
                                    $attendee_has_shirt_size = youth_has_value($attendee_shirt_size, 'None');
                                    $shirt_class = $attendee_has_shirt_size ? '' : 'bg-danger';

                                    // Grade & Gender
                                    $attendee_grade = get_field('youth_member_current_grade', $user_id);
                                    $attendee_gender = get_field('youth_member_gender', $user_id);

                                    // No gender, throw an error.
                                    if (!isset($attendee_gender) || $attendee_gender == false || trim($attendee_gender) == '') {
                                        echo youth_alert($attendee_name . ' does not have a gender listed!');
                                    }

                                    if (has_tag('Volunteer Team', $user_id)) {
                                        $breakdown['Chaperone'][$attendee_gender][] = $attendee_name;
                                    } elseif (has_tag('College', $user_id)) {
                                        $breakdown['College'][$attendee_gender][] = $attendee_name;
                                    } else {
                                        if ($attendee_grade >= 6 && $attendee_grade <= 8) {
                                            $breakdown['Student']['Middle'][$attendee_gender][] = $attendee_name;
                                        } elseif ($attendee_grade >= 9 && $attendee_grade <= 12) {
                                            $breakdown['Student']['High'][$attendee_gender][] = $attendee_name;
                                        } else {
                                            echo youth_alert($attendee_name . ' is a student and has no grade!');
                                            $breakdown['Error'][$attendee_gender][] = $attendee_name;
                                        }
                                    }

                                    $attendee_html .= '<tr>';
                                    $attendee_html .= '<td>' . $attendee_name . '</td>';
                                    $attendee_html .= '<td class="' . $shirt_class . '">' . $attendee_shirt_size . '</td>';
                                    $attendee_html .= '<td>' . $attendee_grade . '</td>';
                                    $attendee_html .= '<td class="' . $permission_class . ' text-center"><span class="dashicons ' . $permission_icon . '"></span></td>';
                                    $attendee_html .= '</tr>';

                                }

                                $attendee_html .= youth_build_table_end();
                            }

                            $iMiddleSchoolBoys = count_by_gender($breakdown['Student']['Middle'], 'Male');
                            $iHighSchoolBoys = count_by_gender($breakdown['Student']['High'], 'Male');
                            $iCollegeBoys = count_by_gender($breakdown['College'], 'Male');
                            $iChaperoneBoys = count_by_gender($breakdown['Chaperone'], 'Male');

                            $iMiddleSchoolGirls = count_by_gender($breakdown['Student']['Middle'], 'Female');
                            $iHighSchoolGirls = count_by_gender($breakdown['Student']['High'], 'Female');
                            $iCollegeGirls = count_by_gender($breakdown['College'], 'Female');
                            $iChaperoneGirls = count_by_gender($breakdown['Chaperone'], 'Female');

                            $iMiddleSchoolTotal = $iMiddleSchoolBoys + $iMiddleSchoolGirls;
                            $iHighSchoolTotal = $iHighSchoolBoys + $iHighSchoolGirls;
                            $iCollegeTotal = $iCollegeBoys + $iCollegeGirls;
                            $iChaperoneTotal = $iChaperoneBoys + $iChaperoneGirls;

                            $iStudentTotal = $iMiddleSchoolTotal + $iHighSchoolTotal + $iCollegeTotal;
                            $iBoyTotal = $iMiddleSchoolBoys + $iHighSchoolBoys + $iCollegeBoys;
                            $iGirlTotal = $iMiddleSchoolGirls + $iHighSchoolGirls + $iCollegeGirls;

                            ?>
                            <style>
                                p {
                                    font-size:1.25em;
                                }
                                h3 {
                                    border-bottom:1px solid #999;
                                    margin-bottom:15px;
                                }
                            </style>
                            <div class="row">
                                <h3>Breakdown</h3>
                                <div class="col-md-4">
                                    <p><strong>Total Attending:</strong> <?php echo $iStudentTotal + $iChaperoneTotal; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Students Attending:</strong> <?php echo $iStudentTotal; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Chaperones Attending:</strong> <?php echo $iChaperoneTotal; ?></p>
                                </div>
                            </div><br/>
                            <div class="row">
                                <h3>Guys Attending</h3>
                                <div class="col-md-3">
                                    <p><strong>Middle School Guys:</strong> <?php echo $iMiddleSchoolBoys; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>High School Guys:</strong> <?php echo $iHighSchoolBoys; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>College Guys:</strong> <?php echo $iCollegeBoys; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Guy Total:</strong> <?php echo $iBoyTotal; ?> students + <?php echo $iChaperoneBoys; ?> adults</p>
                                </div>
                            </div><br/>
                            <div class="row">
                                <h3>Guys Attending</h3>
                                <div class="col-md-3">
                                    <p><strong>Middle School Girls:</strong> <?php echo $iMiddleSchoolGirls; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>High School Girls:</strong> <?php echo $iHighSchoolGirls; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>College Girls:</strong> <?php echo $iCollegeGirls; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>Girl Total:</strong> <?php echo $iGirlTotal; ?> students + <?php echo $iChaperoneGirls; ?> adults</p>
                                </div>
                            </div><br/>
                            <div class="row">
                                <h3>Shirts</h3>
                                <?php foreach ($shirt_size as $size => $number) : ?>
                                    <div class="col-md-2">
                                        <p><strong><?php echo $size; ?>:</strong> <?php echo count($number); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div><br/>
                            <h3>Attendees</h3>
                            <?php
                            echo $attendee_html;
                        // end of user logged in requirement
                        endif;

                    endwhile;
                else :
                    get_template_part('template-parts/content', 'none');
                endif;

            ?>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
