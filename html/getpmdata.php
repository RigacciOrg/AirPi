<?php
require_once('config.php');
// $station_id is now defined.

// Extract one year of data, back from today.
// Strings represent dates in localtime (PHP-server idea of).
$from_day = date('Y-m-d', strtotime('1 year ago', time()));
$to_day = date('Y-m-d', time());
$tomorrow = date('Y-m-d', strtotime('1 day', time()));

// Use a color classification like ARPAT Toscana.
function color_arpat_like($val) {
    if ($val <  10) return '00ff00'; // Verde 1
    if ($val <  20) return 'aaff00'; // Verde 2
    if ($val <  30) return 'ddff00'; // Giallo/Verde
    if ($val <  40) return 'ffff66'; // Giallo
    if ($val <  50) return 'ffcc00'; // Arancio
    return 'dd0000';                 // Rosso
}

$data = array();

if (PG_CONNECT != '') {
    // Use PostgreSQL database.
    // NOTICE: time_stamp field in database is without timezone, it is converted to timezone-aware
    // using the "AT TIME ZONE" construct and presented in localtime (PosgresSQL idea of). To
    // change PostgreSQL idea of localtime use something like: SET TIME ZONE 'America/Los_Angeles';
    // Query parameters are casted to timestamp without timezone and comparison is performed
    // against presented localtime, disregarding timezone.
    if (($db = pg_connect(PG_CONNECT)) !== FALSE) {
        $sql  = "SELECT to_char(time_stamp AT TIME ZONE 'UTC', 'YYYY-MM-DD') AS day, avg(value) AS pm10 FROM data";
        $sql .= " WHERE (time_stamp AT TIME ZONE 'UTC') >= '%s'::TIMESTAMP";
        $sql .= " AND (time_stamp AT TIME ZONE 'UTC') < '%s'::TIMESTAMP";
        $sql .= " AND type = 'pm10'";
        $sql .= " AND station_id = %d GROUP BY day ORDER BY day";
        $sql = sprintf($sql, $from_day, $tomorrow, $station_id);
        //print "$sql\n";
        $result = pg_query($db, $sql);
        while($row = pg_fetch_assoc($result)) {
            //print "Data: " . gettype($row['day']) . ", " . $row['day'] . "\n";
            $year  = (int)substr($row['day'], 0, 4);
            $month = (int)substr($row['day'], 5, 2) - 1;
            $day   = (int)substr($row['day'], 8, 2);
            $val   = (float)$row['pm10'];
            $data[] = array($year, $month, $day, $val, color_arpat_like($val));
        }
    }
} else {
    // Use SQLite database.
    // NOTICE: timestamp field in database is TEXT in ISO 8601 UTC time format ('%Y-%m-%dT%H:%M:%SZ'),
    // it is converted to localtime using the 'localtime' modifier, and then converted to a
    // formatted string with strftime() function. SQLite idea of localtime comes from the operating
    // system, e.g. on Debian GNU/Linux change it with "dpkg-reconfigure tzdata".
    try {
        $db = new SQLite3('/var/lib/airpi/airpi-data.db');
    } catch (Exception $e) {
        error_log('new SQLite3() Exception: ' . $e->getMessage());
        $db = FALSE;
    }
    if ($db) {
        $sql  = "SELECT strftime('%%Y-%%m-%%d', timestamp, 'localtime') AS day, avg(value) AS pm10";
        $sql .= " FROM data WHERE day >= '%s' AND day <= '%s' AND type = 'pm10' GROUP BY day ORDER BY day";
        $sql = sprintf($sql, $from_day, $to_day);
        //print "$sql\n";
        $result = $db->query($sql);
        // For JavaScript Date(): january is month 0.
        while($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $year  = (int)substr($row['day'], 0, 4);
            $month = (int)substr($row['day'], 5, 2) - 1;
            $day   = (int)substr($row['day'], 8, 2);
            $val   = (float)$row['pm10'];
            $data[] = array($year, $month, $day, $val, color_arpat_like($val));
        }
    }
}

header('Content-type: application/json');
echo json_encode($data);
?>
