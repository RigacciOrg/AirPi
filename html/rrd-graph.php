<?php

$graph = 'pressure';
$period = 'weekly';

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
$cmd = '/usr/local/lib/airpi/rrd-graph-' . $graph . ' ' . $period;
passthru($cmd, $err);
exit();
