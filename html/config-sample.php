<?php

// Web interface: PHP configuration file example.
// Copy this file as the 'THIS_CONFIG_PHP' file (it is defined into the
// config-init.php source), it will be used instead of the hard-coded
// defaults. If you make the file writable by the web server user, it
// can be saved (overwritten) calling the local_config_write() PHP
// function.

$config = array();

// Administrator username and password.
$config['users'] = array(
    'admin' => array (
        'passwd' => '$2y$10$3g2zJ6wNNoUVO.VUov5/aui9XkI2znlaN4SQWcqQO44V2A6vX2o72',
        'isadmin' => true));

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

// Directory conaining the config options files (i.e. options,
// options-regexp and options-pending). Options are used by the web
// configuration interface to create system configuration files from
// templates.
$config['config_options_dir'] = '/etc/host-config';

?>
