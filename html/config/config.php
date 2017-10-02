<?php

function config_read() {
    global $config;
    $config_file = join_paths(THIS_CONFIG, 'webconfig.php');
    if (file_exists($config_file)) {
        include $config_file;
    }
}

function config_write() {
    global $config;
    $config_file = join_paths(THIS_CONFIG, 'webconfig.php');
    $config_export = var_export($config, true);
    return file_put_contents($config_file, "<?php\n\$config = ${config_export};\n");
}

// Default (hard-coded) password is admin/secret.
// Do not change this file, edit THIS_CONFIG/webconfig.php instead.
$config = array(
    'admin_user' => 'admin',
    'admin_pass' => '$2y$10$LdZb2R1p6D1uHBdwl9F6jemf0GoFFA0DHjxkwlX7c254YK6VFuqeO',
    'lang'       => 'it_IT'
);
config_read();

?>
