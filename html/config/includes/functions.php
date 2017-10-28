<?php

//-------------------------------------------------------------------------
// Add the token to prevent Cross-Site Request Forgery.
//-------------------------------------------------------------------------
function CSRFToken() {
?>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
<?php
}

//-------------------------------------------------------------------------
// Validate the CSRF Token.
//-------------------------------------------------------------------------
function CSRFValidate() {
    if (hash_equals($_POST['csrf_token'], $_SESSION['csrf_token'])) {
        return TRUE;
    } else {
        error_log('CSRF violation');
        return FALSE;
    }
}

//-------------------------------------------------------------------------
// Join the passed arguments to make a proper path.
//-------------------------------------------------------------------------
function join_paths() {
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') $paths[] = $arg;
    }
    $regexp = '#' . DIRECTORY_SEPARATOR . '+#';
    return preg_replace($regexp, DIRECTORY_SEPARATOR, join(DIRECTORY_SEPARATOR, $paths));
}

//-------------------------------------------------------------------------
//-------------------------------------------------------------------------
function my_html($str) {
    return htmlentities($str, ENT_COMPAT, 'UTF-8');
}

//-------------------------------------------------------------------------
// Return FALSE if string is not a valid IPv4 address.
//-------------------------------------------------------------------------
function is_ipv4($address) {
    return filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
}

//-------------------------------------------------------------------------
// Return FALSE if string is not a valid IPv4 netmask.
//-------------------------------------------------------------------------
function is_netmask($netmask) {
    if (filter_var($netmask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        if (strpos(sprintf("%032b\n", ip2long($netmask)), '01') === FALSE) {
            return $netmask;
        }
    }
    return FALSE;
}

//-------------------------------------------------------------------------
// Return FALSE if $string does not match Perl $regexp.
//-------------------------------------------------------------------------
function is_regexp($string, $regexp) {
    $escaped_regexp = addcslashes($regexp, '/');
    $options = array(
        'options' => array(
            'regexp' => '/' . $escaped_regexp . '/'));
    if (filter_var($string, FILTER_VALIDATE_REGEXP, $options) === FALSE) {
        return FALSE;
    } else {
        return TRUE;
    }
}

//-------------------------------------------------------------------------
// Return 'Yes' or 'No' upon a "checkbox" type $_REQUEST variable.
// If input is unchecked, no values are submitted, so var is unset.
//-------------------------------------------------------------------------
function html_bool($var) {
    if (isset($var)) {
        return 'Yes';
    } else {
        return 'No';
    }
}

//-------------------------------------------------------------------------
// Read option values from file and return them as an associative array.
// TODO: Does we accept leading/trailing spaces in options?
//-------------------------------------------------------------------------
function read_options($file) {
    $opt = array();
    if (($fp = @fopen(join_paths(THIS_CONFIG, $file), 'r')) != FALSE) {
        while ($buffer = fgets($fp, 4096)) {
            if (! preg_match("/^\s*#/", $buffer)) {
                if (strpos($buffer, '=')) {
                    list($key, $val) = explode('=', $buffer, 2);
                    $opt[$key] = rtrim($val);
                }
            }
        }
        fclose($fp);
    }
    return $opt;
}

//-------------------------------------------------------------------------
// Merge the new options with pending ones, write them out only if
// they differ from the current values. Return the count of pending
// options, or FALSE on error.
//-------------------------------------------------------------------------
function merge_pending($opt_new) {
    $opt = read_options('options');
    $opt_pending = read_options('options-pending');
    $opt_pending = array_merge($opt_pending, $opt_new);
    $n = 0;
    if ($fp = @fopen(join_paths(THIS_CONFIG, 'options-pending'), 'w')) {
        // TODO: flock() the file.
        foreach ($opt_pending as $key => $val) {
            if ($opt_pending[$key] != $opt[$key]) {
                if (fwrite($fp, sprintf("%s=%s\n", $key, $val)) === FALSE) {
                    return FALSE;
                } else {
                    $n += 1;
                }
            }
        }
        fclose($fp);
        return $n;
    }
    return FALSE;
}

//-------------------------------------------------------------------------
// Save pending options (but do not apply them).
//-------------------------------------------------------------------------
function save_pending() {
    $opt_pending = read_options('options-pending');
    $opt_lines = file(join_paths(THIS_CONFIG, 'options'), FILE_IGNORE_NEW_LINES);
    if ($opt_lines === FALSE) return FALSE;
    if ($fp = @fopen(join_paths(THIS_CONFIG, 'options'), 'r+')) {
        if (flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            foreach($opt_lines as $line) {
                if (!preg_match("/^\s*#/", $line)) {
                    if (strpos($line, '=')) {
                        list($key, $val) = explode('=', $line, 2);
                        if (array_key_exists($key, $opt_pending)) {
                            if ($opt_pending[$key] != $val) {
                                $line = sprintf("%s=%s", $key, $opt_pending[$key]);
                            }
                        }
                    }
                }
                if (fwrite($fp, $line . "\n") === FALSE) {
                    error_log('Error writing to options file');
                    return FALSE;
                }
            }
        } else {
            error_log('Cannot lock options file');
            fclose($fp);
            return FALSE;
        }
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    } else {
        error_log('Error opening options file');
        return FALSE;
    }
    // Remove pending options.
    @file_put_contents(join_paths(THIS_CONFIG, 'options-pending'), '', LOCK_EX);
    return TRUE;
}

//-------------------------------------------------------------------------
// Apply options to all the configuration files.
//-------------------------------------------------------------------------
function apply_config() {
    $cmd = 'sudo /usr/local/sbin/host-config';
    $output = array();
    array_push($output, 'Executing host-config:');
    exec($cmd, $output, $return_var);
    array_push($output, 'Return code = ' . $return_var);
    print "<pre>\n";
    foreach($output as $line) print $line . "\n";
    print "</pre>\n";
    return $return_var;
}

?>
