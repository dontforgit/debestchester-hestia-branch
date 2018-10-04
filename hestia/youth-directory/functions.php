<?php

/**
 * Debug function to dump variable contents and optionally die.
 *
 * @param $vVariable
 *             Variable to dump out contents.
 * @param bool $vDie
 *             Optional: Should execute of the script halt?
 */
function echo_pre($vVariable, $vDie = false)
{
    if (is_object($vVariable) || is_array($vVariable)) {
        echo "<pre>", print_r($vVariable), "</pre>";
    } else {
        var_dump($vVariable);
    }

    if ($vDie) {
        die;
    }
}

/**
 * Returns a formatted array of attendance records for the past x number of months.
 *
 * @param int $user_id
 *             The ID of the user to check
 * @param int $months_in_the_past
 *             The number of months in the past
 *
 * @return array
 */
function get_youth_directory_attendance($user_id, $months_in_the_past = 1)
{
    global $wpdb;
    $date = date('Y-m-d', strtotime('-' . $months_in_the_past . 'months'));
    $sql = "SELECT * FROM wp_attendance WHERE attend_id = {$user_id} AND attend_date > {$date}";
    $attendance_results = $wpdb->get_results($sql);

    $results = array(
        'sunday_school' => 0,
        'sunday_night' => 0,
        'wednesday_night' => 0,
    );
    foreach ($attendance_results as $event) {
        $results[$event->attend_event]++;
    }
    return $results;
}

function display_youth_directory_attendance($attendance_array)
{
    $HTML = '';
    foreach ($attendance_array as $event_type => $attendance) {
        $HTML .= '<span style="text-decoration:underline;">';
        $HTML .= ucwords(preg_replace('/_/', ' ', $event_type));
        $HTML .= '</span>';
        $HTML .= ' - ' . $attendance . '<br/>';
    }
    return $HTML;
}