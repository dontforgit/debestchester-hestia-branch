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
    $sql = "SELECT * FROM wp_attendance WHERE attend_id = {$user_id} AND attend_date > '{$date}'";
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

/**
 * Build the HTML for the attendance on the single-youth_directory page.
 *
 * @param array $attendance_array
 *            Array of attendance information.
 *
 * @return string
 */
function display_youth_directory_attendance($attendance_array)
{
    $HTML = '';
    $total = 0;
    foreach ($attendance_array as $event_type => $attendance) {
        $HTML .= '<span style="text-decoration:underline;">';
        $HTML .= ucwords(preg_replace('/_/', ' ', $event_type));
        $HTML .= '</span>';
        $HTML .= ' - ' . $attendance . '<br/>';
        $total += $attendance;
    }
    $HTML .= '<span style="text-decoration:underline;">Total</span> - ' . $total;
    return $HTML;
}

/**
 * Queries the youth_directory for all members and sorts them
 * based on order of importance.
 *
 * @return array
 */
function get_youth_directory_classifications()
{
    // Customized Youth Directory Query
    $args = array(
        'post_type' => 'youth_directory',
        'posts_per_page' => -1,
        'orderby' => array(
            'title' => 'ASC'
        ),
    );
    $directory = new WP_Query($args);

    // Build display array
    $classifications = array(
        'Member' => array(),
        'Volunteer Team' => array(),
        'Guest' => array(),
        'Big Event' => array(),
        'College' => array(),
        'Inactive' => array(),
    );

    // Populate the classifications array
    while ($directory->have_posts()) {
        $directory->the_post();

        $user_information = array(
            'title' => get_the_title(),
            'link' => get_the_permalink(),
            'id' => get_the_ID()
        );

        if (has_tag('Inactive')) {
            array_push($classifications['Inactive'], $user_information);
        } elseif (has_tag('College')) {
            array_push($classifications['College'], $user_information);
        } elseif (has_tag('Big Event')) {
            array_push($classifications['Big Event'], $user_information);
        } elseif (has_tag('Volunteer Team')) {
            array_push($classifications['Volunteer Team'], $user_information);
        } elseif (has_tag('Guest')) {
            array_push($classifications['Guest'], $user_information);
        } else {
            array_push($classifications['Member'], $user_information);
        }
    }
    return $classifications;
}

/**
 * Counts the number of 'Male' or 'Female' students exist within that key of the array.
 * Helper function to de-clutter the big event display.
 *
 * @param array $array
 *            Array of of students with keys 'Male' and 'Female'
 * @param string $gender
 *            'Male' or 'Female'
 *
 * @return int
 */
function count_by_gender($array, $gender)
{
    if (isset($array[$gender]) && is_array($array[$gender])) {
        return count($array[$gender]);
    }
    return 0;
}

/**
 * Builds the top of a HTML table.
 *
 * @param array $array
 *            The keys to be added to the table head.
 *
 * @return string HTML output.
 */
function youth_build_table_head($array)
{
    $sHTML = '<table><thead><tr>';
    foreach ($array as $heading) {
        $sHTML .= '<th>' . $heading . '</th>';
    }
    $sHTML .= '</tr></thead><tbody>';
    return $sHTML;
}

/**
 * Builds the bottom of a HTML table.
 *
 * @return string HTML output.
 */
function youth_build_table_end()
{
    return '</tbody></table>';
}

/**
 * Check if the ACF value is set and not false.
 * Optional parameter to see if the value is set to 'None' or 'NA', etc.
 *
 * @param string|bool|int $check
 *            Value to check to see if ACF field is set.
 * @param string|bool $optional_check
 *            Optional second value to check if ACF field does not match.
 *
 * @return bool
 */
function youth_has_value($check, $optional_check = false)
{
    if (isset($check) && $check != false) {
        if ($optional_check !== false) {
            if ($optional_check != $check) {
                return true;
            }
            return false;
        }
        return true;
    }
    return false;
}

/**
 * Simple function to display alert box with custom text.
 *
 * @param string $text
 *            Text to be displayed in the alert box.
 *
 * @return string
 */
function youth_alert($text)
{
    $html = '<br/><div class="row">';
    $html .= '<div class="col-md-12 bg-danger"><p><strong>Oh snap!</strong> ' . $text . '</p></div>';
    $html .= '</div>';
    return $html;
}
