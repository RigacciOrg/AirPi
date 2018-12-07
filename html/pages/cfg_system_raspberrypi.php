<?php

//-------------------------------------------------------------------------
// Lookup table from
// http://www.raspberrypi-spy.co.uk/2012/09/checking-your-raspberry-pi-board-version/
//-------------------------------------------------------------------------
function sys_RPiVersion() {
    $revisions = array(
        '0002'   => 'Model B Revision 1.0',
        '0003'   => 'Model B Revision 1.0 + ECN0001',
        '0004'   => 'Model B Revision 2.0 (256 MB)',
        '0005'   => 'Model B Revision 2.0 (256 MB)',
        '0006'   => 'Model B Revision 2.0 (256 MB)',
        '0007'   => 'Model A',
        '0008'   => 'Model A',
        '0009'   => 'Model A',
        '000d'   => 'Model B Revision 2.0 (512 MB)',
        '000e'   => 'Model B Revision 2.0 (512 MB)',
        '000f'   => 'Model B Revision 2.0 (512 MB)',
        '0010'   => 'Model B+',
        '0013'   => 'Model B+',
        '0011'   => 'Compute Module',
        '0012'   => 'Model A+',
        'a01041' => 'a01041',
        'a21041' => 'a21041',
        '900021' => 'Model A+ V1.1 (512 MB)',
        '900032' => 'Model B+ V1.2 (512 MB)',
        '900092' => 'PiZero',
        'a02082' => 'Pi 3 Model B',
        'a22082' => 'Pi 3 Model B'
    );
    exec('cat /proc/cpuinfo', $cpuinfo_array);
    $rev = trim(array_pop(explode(':', array_pop(preg_grep('/^Revision/', $cpuinfo_array)))));
    if (array_key_exists($rev, $revisions)) {
        return $revisions[$rev];
    } else {
        return 'Unknown Raspberry Pi';
    }
}

function sys_hostname() {
    exec('hostname -f', $hostarray);
    return $hostarray[0];
}

function sys_uptime() {
    $uparray = explode(' ', exec('cat /proc/uptime'));
    $seconds = round($uparray[0], 0);
    $minutes = $seconds / 60;
    $hours   = $minutes / 60;
    $days    = floor($hours / 24);
    $hours   = floor($hours   - ($days * 24));
    $minutes = floor($minutes - ($days * 24 * 60) - ($hours * 60));
    $uptime= '';
    if ($days    != 0) { $uptime .= $days    . ' ' . (($days    > 1)? _('days'):_('day')) . ' '; }
    if ($hours   != 0) { $uptime .= $hours   . ' ' . (($hours   > 1)? _('hours'):_('hour')) . ' '; }
    if ($minutes != 0) { $uptime .= $minutes . ' ' . (($minutes > 1)? _('minutes'):_('minute')) . ' '; }
    return $uptime;
}

function sys_memused() {
    exec("cat /proc/meminfo | awk '/MemTotal:/ { total=$2 } /MemAvailable:/ { available=$2 } END { print (1-(available/total))*100}'", $memarray);
    $memused = floor($memarray[0]);
    if     ($memused > 80) { $memused_status = 'danger';  }
    elseif ($memused > 60) { $memused_status = 'warning'; }
    else                   { $memused_status = 'success'; }
    return array($memused, $memused_status);
}

function sys_cpuload() {
    $cores   = exec('grep -c ^processor /proc/cpuinfo');
    $loadavg = exec("awk '{print $1}' /proc/loadavg");
    $cpuload = floor(($loadavg * 100) / $cores);
    if     ($cpuload >  80) { $cpuload_status = 'danger';  }
    elseif ($cpuload >  60) { $cpuload_status = 'warning'; }
    else                    { $cpuload_status = 'success'; }
    return array($cpuload, $cpuload_status);
}

function sys_network() {
    $macaddress = '';
    $ipaddress = '';
    $netmask = '';
    $signal = FALSE;
    exec('ip route list match 0.0.0.0', $ip_route);
    $ip_route_array = explode(' ' , $ip_route[0]);
    $gateway = $ip_route_array[2];
    $device = $ip_route_array[4];
    exec('ifconfig ' . escapeshellarg($device), $ifconfig_array);
    $ifconfig = preg_replace('/\s\s+/', ' ', implode(' ', $ifconfig_array));
    preg_match('#HWaddr ([0-9a-f:]+)#i', $ifconfig, $result);
    if (count($result) > 1) $macaddress = $result[1];
    preg_match('#inet addr:([0-9\.]+)#i', $ifconfig, $result);
    if (count($result) > 1) $ipaddress = $result[1];
    preg_match('#Mask:([0-9\.]+)#i', $ifconfig, $result);
    if (count($result) > 1) $netmask = $result[1];
    exec('iwconfig ' . escapeshellarg($device), $iwconfig_array);
    $iwconfig = preg_replace('/\s\s+/', ' ', implode(' ', $iwconfig_array));
    preg_match('#Signal level=([0-9]+)#i', $iwconfig, $result);
    if (count($result) > 1) {
        $signal = $result[1];
        if (is_numeric($signal)) {
            $signal = ($signal >= 0 and $signal <= 100) ? (int)$signal : FALSE;
        }
    }
    return array($device, $macaddress, $ipaddress, $netmask, $gateway, $signal);
}

?>
