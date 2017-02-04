<?php

require_once('config.php');

function my_html($str) {
    return(htmlentities($str, ENT_COMPAT, 'UTF-8'));
}

function pm10_icon($val) {
    if ($val === NULL) return 'rof-car-smoke';
    if     ($val < 20) return 'rof-flower-4';
    elseif ($val < 40) return 'rof-car-smoke';
    elseif ($val < 60) return 'rof-plant-smoke';
    elseif ($val < 80) return 'rof-gas-mask';
    else               return 'rof-skull';
}

function pressure_icon($val) {
    if   ($val === NULL) return 'rof-weather';
    if     ($val <  980) return 'rof-weather-clouds-rain';
    elseif ($val < 1000) return 'rof-weather-clouds';
    elseif ($val < 1020) return 'rof-weather-sun-cloud';
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

function get_snmp_data() {
    $sensors = array('temperature', 'pressure', 'humidity', 'pm10');
    $values = array();
    foreach($sensors as $key) $values[$key] = NULL;
    $cmd = 'rrdtool lastupdate /var/lib/airpi/airpi-data.rrd';
    exec($cmd, $output, $retval);
    $val = preg_split('/[\s]+/', trim($output[2]));
    if (count($val) >= 10) {
        $timestamp = (int)substr($val[0], 0, -1);
        if ((time() - $timestamp) < 600) {
            // TODO: What if values are not available?
            $values['temperature'] = (float)$val[1] / 1000.0;
            $values['pressure']    = (float)$val[2] / 1000.0;
            $values['humidity']    = (float)$val[3] / 1000.0;
            $values['pm10']        = (float)$val[9];
        }
    }
    return $values;
}

// Calculate atmospheric pressure variation in the last 3 hours (average on 30 minutes).
function pressure_diff_3h() {
    // Meaning of pressure value (below 500 m.s.l.m.)
    //  > 1025 hPa Nice wheater
    //  < 1000 hPa Bad weather
    // Meaning of pressure variation in 3 hours:
    //  < 1 hPa Weather is stable
    //  < 2 hPa Expected weather variation in 24-48 next hours.
    //  < 3 hPa Expected weather variation in 12-24 next hours.
    //  < 6 hPa Weather variation occurring now.
    $db = new SQLite3('/var/lib/airpi/airpi-data.db');
    $sql  = "SELECT avg(value) AS p FROM data WHERE %s AND type = 'p'";
    $intvl1 = "datetime(timestamp) BETWEEN datetime('now', '-3 hours', '-30 minutes') AND datetime('now', '-3 hours')";
    $intvl2 = "datetime(timestamp) BETWEEN datetime('now', '-30 minutes') AND datetime('now')";
    $sql1 = sprintf($sql, $intvl1);
    $p1 = $db->querySingle($sql1);
    $sql2 = sprintf($sql, $intvl2);
    $p2 = $db->querySingle($sql2);
    if ($p1 !== FALSE and $p2 !== FALSE) {
        return $p2 - $p1;
    } else {
        return NULL;
    }
}

?>
