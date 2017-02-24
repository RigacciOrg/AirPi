<?php

// Example of GET requests:
//   * download_csv.php?id=0&period=curr_day
//   * download_csv.php?id=0&period=curr_day&data[]=pm10&data[]=hum

require_once('functions.php');
// $station_id is now defined.

$periods = array ('day', 'week', 'month', 'year');
$valid_fields = array('t', 'p', 'hum', 'pm10');
//  t     Temperature
//  p     Pressure
//  hum   Humidity
//  pm10  PM10 concentration
//  pm2.5 PM2.5 concentration

if (array_key_exists('period', $_REQUEST)) {
    $request = $_REQUEST['period'];
    $today = gmmktime();
    switch ($request) {
        case 'curr_day':
            $begin = $today;
            $end   = strtotime('+1 day', $today);
            break;
        case 'curr_week':
            $begin = strtotime("next Monday -1 week", $today);
            $end   = strtotime('+1 day', $today);
            break;
        case 'curr_month':
            $begin = strtotime("first day of this month", $today);
            $end   = strtotime('+1 day', $today);
            break;
        case 'curr_year':
            $begin = strtotime("first day of January this year", $today);
            $end   = strtotime('+1 day', $today);
            break;
        case 'prev_day':
            $begin = strtotime('-1 day', $today);
            $end   = $today;
            break;
        case 'prev_week':
            $begin = strtotime("next Monday -2 week", $today);
            $end   = strtotime("next Monday -1 week", $today);
            break;
        case 'prev_month':
            $begin = strtotime("first day of previous month", $today);
            $end   = strtotime("first day of this month", $today);
            break;
        case 'prev_year':
            $begin = strtotime("first day of January previous year", $today);
            $end   = strtotime("first day of January this year", $today);
            break;
        default:
            $begin = $end = NULL;
            break;
    }
    if ($begin != NULL and $end != NULL) {
        $begin_date = strftime('%Y-%m-%d', $begin);
        $end_date   = strftime('%Y-%m-%d', $end);
        $req_data   = array();
        // Requested fields are passed through an array of checkboxes name="data[]".
        if (array_key_exists('data', $_REQUEST)) {
            // Ignore unwanted values from $_REQUEST['data'].
            foreach ($_REQUEST['data'] as $f) {
                if (in_array($f, $valid_fields)) array_push($req_data, $f);
            }
        }
        $cmd  = '/usr/local/lib/airpi/airpi-data-export';
        $cmd .= ' ' . escapeshellarg($station_id);
        $cmd .= ' ' . escapeshellarg($begin_date);
        $cmd .= ' ' . escapeshellarg($end_date);
        if (count($req_data) > 0) {
            $cmd .= ' ' . escapeshellarg(implode(',', $req_data));
        }
        //print $cmd . "<br>\n";
        //error_log(basename(__FILE__) . ': Executing: ' . $cmd);
        header('Content-Type: text/csv; charset=utf-8');
        $filename = sprintf('airpi_%s_%s_%s.csv', sanitize_name(STATION_NAME), $begin_date, $end_date);
        header("Content-Disposition: attachment; filename=\"$filename\"");
        passthru($cmd, $ret);
        //error_log(basename(__FILE__) . ': Command returned ' . $ret);
        exit();
    }
}
