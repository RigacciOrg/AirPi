<?php

// Store sensors data from remote stations into a datacenter database.

require_once('db_dsn.php');

// ISO 8601 UTC timestamp format. See strftime() PHP function.
define('TIMESTAMP_FORMAT', '%Y-%m-%dT%H:%M:%SZ');  

$login = (isset($_REQUEST['login'])) ? $_REQUEST['login'] : NULL;
$password = (isset($_REQUEST['password'])) ? $_REQUEST['password'] : NULL;
if ($login == NULL) exit('Invalid login');

// Connect to the database, use the MDB2 abstraction.
$mdb2 = MDB2::connect($dsn);
if (PEAR::isError($mdb2)) exit($mdb2->getMessage());
$mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

// Authenticate the client.
$sql = 'SELECT id, password FROM stations WHERE login = ?';
$query = $mdb2->prepare($sql, array('text'), array('integer', 'text'));
$result = $query->execute($login);
if (PEAR::isError($result)) exit($result->getMessage());
$station = $result->fetchRow();
$query->free();
$result->free();
if (! isset($station['password'])) exit('Login failed');
if ($station['password'] != md5($password)) exit('Login failed');

// Received some data, INSERT into the database.
if (isset($_REQUEST['sensors_data'])) {
    $sensors_data = json_decode($_REQUEST['sensors_data']);
    //error_log("JSON decoded sensors_data = " . print_r($sensors_data, true));
    // Open a transaction
    $res = $mdb2->beginTransaction();
    $sql = 'INSERT INTO data (station_id, time_stamp, type, value) VALUES (?, ?, ?, ?)';
    $query = $mdb2->prepare($sql, array('integer', 'text', 'text', 'float'));
    if (PEAR::isError($query)) exit($query->getMessage());
    // TODO: Insert the whole array with a single query?
    // TODO: Enclose in BEGIN/COMMIT
    foreach($sensors_data as $row) {
        array_unshift($row, $station['id']);
        $result = $query->execute($row);
        if (PEAR::isError($result)) {
            $mdb2->rollback();
            exit($result->getMessage());
        }
    }
    $mdb2->commit();
    $query->free();
}

// Get the timestamp of last inserted data.
$sql = 'SELECT max(time_stamp) AS time_stamp FROM data WHERE station_id = ?';
$query = $mdb2->prepare($sql, array('integer'), array('timestamp'));
if (PEAR::isError($query)) exit($query->getMessage());
$result = $query->execute($station['id']);
if (PEAR::isError($result)) exit($result->getMessage());
$row = $result->fetchRow();
$query->free();
$result->free();
if ($row['time_stamp'] == NULL) {
    // Unix Epoch (January 1 1970 00:00:00 GMT)
    $utc_timestamp = gmstrftime(TIMESTAMP_FORMAT, 0);
} else {
    $utc_timestamp = substr($row['time_stamp'], 0, 10) . 'T' . substr($row['time_stamp'], 11, 8) . 'Z';
    if (!preg_match('/^\d{4}-\d\d-\d\dT\d\d:\d\d:\d\dZ$/', $utc_timestamp)) {
        exit('Invalid database timestamp format');
    }
}

$mdb2->disconnect();

header('Content-type: application/json');
echo json_encode(array(
    'name' => $login,
    'latest_timestamp' => $utc_timestamp
));

?>
