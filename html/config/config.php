<?php

// Default (hard-coded) configuration.
$config = array(
    'admin_user' => 'admin',
    'admin_pass' => '$2y$10$YKIyWAmnQLtiJAy6QgHQ.eCpY4m.HCEbiHaTgN6.acNC6bDElzt.i',
    'lang'       => 'it_IT'
);

// Customized configuration is read from file.
if ($auth_details = @fopen(join_paths(THIS_CONFIG, 'webconfig.cfg'), 'r')) {
    $line = trim(fgets($auth_details));
    if ($line != '') {
        $config['admin_user'] = $line;
        $config['admin_pass'] = trim(fgets($auth_details));
    }
    fclose($auth_details);
}

?>
