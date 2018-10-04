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
$results = $wpdb->get_results($sql);
echo_pre($results,1);
