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

foreach ($attendees as $attendee) {
    $data = array(
        'attend_date' => $date,
        'attend_event' => $event,
        'attend_id' => $attendee
    );
    $wpdb->insert($table_name, $data);
}