<?php
/**
 * Created by PhpStorm.
 * User: joshuabryant
 * Date: 2/18/19
 * Time: 10:55 AM
 */

class eventAttendance
{
    public $eventName = '';
    public $properName = '';
    public $data = '';
    public $highest = 0;
    public $lowest = 999;
    public $average = 0;
    public $resultsArray = [];
    public $attendanceBreakdownHTML;

    public function __construct($event_name)
    {
        $this->setEventName($event_name);
        $this->buildAttendanceResults();
        $this->determineHighestLowest();
        $this->buildAttendanceData();
        $this->buildAttendanceBreakdownHTML();
    }

    public function getData() {
        return $this->data;
    }

    public function getProperName() {
        return $this->properName;
    }

    public function getAttendanceAverage() {
        return floor($this->average);
    }

    public function getAttendanceBreakdown() {
        return $this->attendanceBreakdownHTML;
    }

    public function setEventName($event_name) {
        $this->eventName = $event_name;
        $this->properName = ucwords(preg_replace('/_/', ' ', $event_name));
    }

    public function buildAttendanceBreakdownHTML()
    {
        $HTML = '';
        foreach ($this->resultsArray as $attendance_day) {
            if ($attendance_day->cnt == $this->highest) {
                $attendance_color = 'red';
            } elseif ($attendance_day->cnt == $this->lowest) {
                $attendance_color = 'blue';
            } else {
                $attendance_color = 'default';
            }
            $HTML .= '<div class="col-md-3">';
            $HTML .= '<p style="padding:5px;background-color:#eee;color:' . $attendance_color . '">';
            $HTML .= date("F j, Y", strtotime($attendance_day->attend_date)) . '<br/>';
            $HTML .= ucwords(preg_replace('/_/', ' ', $attendance_day->attend_event));
            $HTML .= ' : <strong>' . $attendance_day->cnt . '</strong>';
            $HTML .= '</p>';
            $HTML .= '</div>';
        }
        $this->attendanceBreakdownHTML = $HTML;
    }

    public function buildAttendanceData() {
        foreach ($this->resultsArray as $attendance_result) {
            $this->data .= '{ y:' . $attendance_result->cnt;
            if ($attendance_result->cnt == $this->highest) {
                $this->data .=  ', indexLabel: "highest",markerColor: "red", markerType: "triangle"';
            } elseif ($attendance_result->cnt == $this->lowest) {
                $this->data .=  ', indexLabel: "lowest",markerColor: "DarkSlateGrey", markerType: "cross"';
            }
            $this->data .=  ' },';
        }
    }

    public function determineHighestLowest() {
        foreach ($this->resultsArray as $attendance_result) {
            if ($attendance_result->cnt > $this->highest) {
                $this->highest = $attendance_result->cnt;
            } elseif ($attendance_result->cnt < $this->lowest) {
                $this->lowest = $attendance_result->cnt;
            }
            $this->average = (($this->average + $attendance_result->cnt) / 2);
        }
    }

    public function buildAttendanceResults() {
        global $wpdb;
        $sql = "SELECT a.attend_date, COUNT(a.attend_date) AS cnt, a.attend_event
                FROM wp_attendance a
                WHERE a.attend_event LIKE '%{$this->eventName}%'
                GROUP BY a.attend_date, a.attend_event
                ORDER BY a.attend_date ASC, a.attend_event DESC;";
        $this->resultsArray = $wpdb->get_results($sql);
    }

}