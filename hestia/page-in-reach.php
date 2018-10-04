<?php
// Include Youth Directory functions file
require_once dirname(__FILE__ ) . '/youth-directory/functions.php';

global $wpdb;
$one_month_ago = date('Y-m-d', strtotime('-1month'));

// Get everyone who has attended.
$sql = "SELECT p.post_title AS attendee, COUNT(a.attend_id) AS attendance_count, a.attend_id AS user_id 
        FROM wp_attendance a 
            LEFT JOIN wp_posts p ON a.attend_id = p.ID
        WHERE a.attend_date > '{$one_month_ago}'
        GROUP BY a.attend_id;";
$attendance_results = $wpdb->get_results($sql);

$attendance = array();
foreach($attendance_results as $result) {
    $attendance[$result->user_id] = $result->attendance_count;
}

$sql = "SELECT * FROM wp_posts p where p.post_type = 'youth_directory' AND p.post_status = 'publish';";
$directory_results = $wpdb->get_results($sql);

foreach($directory_results as $attendee) {
    echo $attendee->post_title . ' - ';
    if (isset($attendance[$attendee->ID])) {
        echo $attendance[$attendee->ID];
    } else {
        echo '0';
    }
    echo '<br/>';
}
