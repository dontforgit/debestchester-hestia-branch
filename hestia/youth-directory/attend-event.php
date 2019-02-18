<?php
// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/functions.php';
require_once dirname(__FILE__ ) . '/eventAttendance.php';

$oEventAttendance = new eventAttendance($_SESSION['attend_event']);

// Default Hestia information
get_header();
do_action('hestia_before_single_post_wrapper');
$default = hestia_get_blog_layout_default();
$sidebar_layout = apply_filters('hestia_sidebar_layout', get_theme_mod('hestia_blog_sidebar_layout', $default));
$wrap_class = apply_filters('hestia_filter_single_post_content_classes', 'col-md-8 single-post-container');
?>

<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            title:{
                text: "<?php echo $oEventAttendance->getProperName();?> Attendance"
            },
            axisY:{
                includeZero: false
            },
            data: [
                {
                    type: "line",
                    dataPoints: [
                        <?php echo $oEventAttendance->getData(); ?>
                    ]
                }
            ]
        });
        chart.render();
    }
</script>

<div class="<?php echo hestia_layout(); ?>">
    <div class="blog-post blog-post-wrapper">
        <div class="container">
            <article id="post-<?php the_ID(); ?>" class="section section-text">
                <div class="btn-group" style="text-align:center;">
                    <a href="/attendance-data/" class="btn btn-warning">All Data</a>
                    <a href="/attendance-ss/" class="btn btn-warning">Sunday School</a>
                    <a href="/attendance-sn/" class="btn btn-warning">Sunday Night</a>
                    <a href="/attendance-wn/" class="btn btn-warning">Wednesday Night</a>
                </div><br/>
                <h3 style="text-align:center;">Average Attendance: <?php echo $oEventAttendance->getAttendanceAverage(); ?></h3>
                <?php
                if (!is_user_logged_in()) :
                    get_template_part('youth-directory/login-override');
                else : ?>
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div><br/><br/>
                    <div class="row">
                        <h3>Easy Breakdown By Date:</h3>
                        <?php echo $oEventAttendance->getAttendanceBreakdown(); ?>
                    </div>
                    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                    <br/><br/>
                <?php endif; ?>
            </article>
        </div>
    </div>
</div>
<div class="footer-wrapper">
    <?php get_footer(); ?>
