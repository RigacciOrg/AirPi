<?php

// Extract one year data back from today.
$from_day = date('Y-m-d', strtotime('1 year ago', time()));
$to_day = date('Y-m-d', time());

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

$db = new SQLite3('/var/lib/airpi/airpi-data.db');
$sql  = "SELECT strftime('%%Y-%%m-%%d', timestamp, 'localtime') AS day, avg(value) AS pm10";
$sql .= " FROM data WHERE day >= '%s' AND day <= '%s' AND type = 'pm10' GROUP BY day ORDER BY day";
$sql = sprintf($sql, $from_day, $to_day);
//print "$sql\n";
$result = $db->query($sql);
// For JavaScript Date(): january is month 0.
while($res = $result->fetchArray(SQLITE3_ASSOC)) {
    $year  = (int)substr($res['day'], 0, 4);
    $month = (int)substr($res['day'], 5, 2) - 1;
    $day   = (int)substr($res['day'], 8, 2);
    $val   = (float)$res['pm10'];
    $data[] = array($year, $month, $day, $val, color_arpat_like($val));
}

header('Content-type: application/json');
echo json_encode($data);
?>
