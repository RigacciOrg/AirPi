<?php

require_once('functions.php');

$graph = 'pressure';
$period = 'weekly';

// Get station_id from $_REQUEST['id'] and sanitize it. Default to '0'.
$station_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$station_id = (preg_match('/^\d{1,3}$/', $station_id)) ? $station_id : '0';

$valid_graphs = array('pressure', 'temperature', 'humidity', 'pm-concentration', 'pm-count');
$valid_periods = array('daily', 'weekly', 'monthly', 'yearly');

if (array_key_exists('graph', $_REQUEST)) {
    $request = $_REQUEST['graph'];
    if (in_array($request, $valid_graphs)) {
        $graph = $request;
    }
}

if (array_key_exists('period', $_REQUEST)) {
    $request = $_REQUEST['period'];
    if (in_array($request, $valid_periods)) {
        $period = $request;
    }
}

header('Pragma: public');
header('Cache-Control: max-age=300');
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 300));
header('Content-Type: image/png');
$cmd = '/usr/local/lib/airpi/rrd-graph-' . $graph;
$cmd .= ' ' . escapeshellarg($period) . ' ' . escapeshellarg($station_id);
passthru($cmd, $err);
exit();
