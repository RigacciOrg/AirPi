<?php

// Title used in web pages, etc.
$config['app_title'] = 'AirPi Station';

// Identification of this host (location, etc.).
$config['station_name'] = 'Local AirPi Station';

// See available locales in "locale" directory.
$config['lang'] = 'en_US';

// Leave blank to use SQLite.
$config['pg_connect'] = '';

// Ignore RRD data if older than (seconds).
$config['stale_rrd'] = 900;

// Directory conaining config options files: options, options-regexp
// and options-pending. Used by the web configuration interface.
$config['config_options_dir'] = '/etc/host-config';

?>
