<?php

// $station_id is always "0" for the AirPi station itself.
// It is used by an AirPi web datacenter, to show different stations.
$station_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';

define('STATION_NAME', 'My AirPi Station');
define('APP_TITLE',    'AirPi Station');
define('PG_CONNECT', '');   // Leave blank to use SQLite.
define('STALE_RRD', 900);   // Ignore RRD data if older than (seconds).

?>
