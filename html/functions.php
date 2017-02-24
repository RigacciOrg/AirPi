<?php

require_once('config.php');

function my_html($str) {
    return(htmlentities($str, ENT_COMPAT, 'UTF-8'));
}

// Remove all non-alphanumeric chars from a string.
function sanitize_name($str) {
    return strtolower(preg_replace('/[^0-9A-Z]+/i', '_', $str));
}

// Like sprintf(), but accepts only two arguments.
// Return 'N/A' if value is NULL.
function my_sprintf($format, $arg) {
    if ($arg === NULL) return 'N/A';
    return sprintf($format, $arg);
}

function pm10_icon($val) {
    if ($val === NULL) return 'rof-car-smoke';
    if     ($val < 20) return 'rof-flower-4';
    elseif ($val < 40) return 'rof-car-smoke';
    elseif ($val < 60) return 'rof-plant-smoke';
    elseif ($val < 80) return 'rof-gas-mask';
    else               return 'rof-skull';
}

// Choose a wather icon upon the absolute pressure value.
// TODO: Pressure should be adjusted by elevation above sea level.
// TODO: The weater icon should be choosen by pressure variation, humidity, etc.
function pressure_icon($val) {
    if   ($val === NULL) return 'rof-weather';
    if     ($val <  990) return 'rof-weather-clouds-rain';
    elseif ($val < 1000) return 'rof-weather-clouds';
    elseif ($val < 1010) return 'rof-weather-sun-cloud';
    else                 return 'rof-weather-sun';
}

function tendency_icon($val) {
    if ($val === NULL) return '';
    if     ($val < -3) return 'rof-arrow-down-3';
    elseif ($val < -2) return 'rof-arrow-down-2';
    elseif ($val < -1) return 'rof-arrow-down-1';
    elseif ($val <  1) return 'rof-arrows_left_right';
    elseif ($val <  2) return 'rof-arrow-up-1';
    elseif ($val <  3) return 'rof-arrow-up-2';
    else               return 'rof-arrow-up-3';
}

// Return the most recent sensor values, from the RRD archive.
// Return NULL for unavailable values or older than STALE_RRD seconds.
function get_latest_data($station_id='0') {
    if (!preg_match('/^\d{1,3}$/', $station_id)) $station_id = '0';
    $sensors = array('temperature', 'pressure', 'humidity', 'pm10');
    $values = array();
    foreach($sensors as $key) $values[$key] = NULL;
    $rrd = sprintf('/var/lib/airpi/airpi-data-%d.rrd', $station_id);
    $cmd = 'rrdtool lastupdate ' . escapeshellarg($rrd);
    if (file_exists($rrd)) {
        exec($cmd, $output, $retval);
        if ($retval == 0) {
            $val = preg_split('/[\s]+/', trim($output[2]));
            if (count($val) >= 10) {
                $timestamp = (int)substr($val[0], 0, -1);
                if ((time() - $timestamp) < STALE_RRD) {
                    // See airpi-data-store-rrd script for fields order.
                    $values['temperature'] = ($val[1] == 'U') ? NULL : (float)$val[1] / 1000.0;
                    $values['pressure']    = ($val[2] == 'U') ? NULL : (float)$val[2] / 1000.0;
                    $values['humidity']    = ($val[3] == 'U') ? NULL : (float)$val[3] / 1000.0;
                    $values['pm10']        = ($val[9] == 'U') ? NULL : (float)$val[9];
                }
            }
        }
    }
    return $values;
}


// Calculate atmospheric pressure variation in the last 3 hours (average on 30 minutes).
// Meaning of pressure variation in 3 hours:
//  < 1 hPa Weather is stable
//  < 2 hPa Expected weather variation in 24-48 next hours.
//  < 3 hPa Expected weather variation in 12-24 next hours.
//  < 6 hPa Weather variation occurring now.
function pressure_diff_3h() {
    if (PG_CONNECT != '') {
        return pressure_diff_pgsql();
    } else {
        return pressure_diff_sqlite();
    }
}

// Use SQLite database.
function pressure_diff_sqlite() {
    try {
        $db = new SQLite3('/var/lib/airpi/airpi-data.db');
    } catch (Exception $e) {
        error_log('pressure_diff_sqlite() Exception: ' . $e->getMessage());
        return NULL;
    }
    $sql  = "SELECT avg(value) AS p FROM data WHERE %s AND type = 'p'";
    $intvl1 = "datetime(timestamp) BETWEEN datetime('now', '-3 hours', '-30 minutes') AND datetime('now', '-3 hours')";
    $intvl2 = "datetime(timestamp) BETWEEN datetime('now', '-30 minutes') AND datetime('now')";
    $sql1 = sprintf($sql, $intvl1);
    $p1 = $db->querySingle($sql1);
    $sql2 = sprintf($sql, $intvl2);
    $p2 = $db->querySingle($sql2);
    //error_log(sprintf('pressure_diff_sqlite(): $p1 = %s, %s, $p2 = %s, %s', gettype($p1), $p1, gettype($p2), $p2));
    if ($p1 != FALSE and $p1 != NULL and $p2 != FALSE and $p2 != NULL) {
        return $p2 - $p1;
    }
    error_log('pressure_diff_sqlite(): Cannot calculate, return NULL');
    return NULL;
}

// Use PostgreSQL database.
function pressure_diff_pgsql() {
    if (($db = pg_connect(PG_CONNECT)) !== FALSE) {
        $sql = "SELECT avg(value) AS p FROM data WHERE %s AND type = 'p'";
        $intvl1 = "time_stamp BETWEEN (now() AT TIME ZONE 'UTC' - INTERVAL '3 hours 30 minutes') AND (now() AT TIME ZONE 'UTC' - INTERVAL '3 hours')";
        $intvl2 = "time_stamp BETWEEN (now() AT TIME ZONE 'UTC' - INTERVAL '30 minutes') AND (now() AT TIME ZONE 'UTC')";
        $sql1 = sprintf($sql, $intvl1);
        $res = pg_query($db, $sql1);
        if ($res) $p1 = pg_fetch_result($res, 0, 0);
        else $p1 = FALSE;
        $sql2 = sprintf($sql, $intvl2);
        $res = pg_query($db, $sql2);
        if ($res) $p2 = pg_fetch_result($res, 0, 0);
        else $p2 = FALSE;
        //error_log(sprintf('pressure_diff_pgsql(): $p1 = %s, %s, $p2 = %s, %s', gettype($p1), $p1, gettype($p2), $p2));
        if ($p1 != FALSE and $p1 != NULL and $p2 != FALSE and $p2 != NULL) {
            // Values are returned as string (sic!).
            return ((float)$p2 - (float)$p1);
        }
    }
    error_log('pressure_diff_pgsql(): Cannot calculate, return NULL');
    return NULL;
}

?>
