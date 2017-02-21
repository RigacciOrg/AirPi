<?php
//
// Web Configuration Framework
//
// Author:      Niccolo Rigacci <niccolo@rigacci.org>
// License:     GNU General Public License, version 3 (GPL-3.0)
//

define('THIS_APP', 'AirPi');
define('THIS_CONFIG', '/etc/host-config');

include_once('includes/functions.php');
include_once('config.php');
include_once('includes/authenticate.php');
include_once('includes/system.php');
include_once('includes/station.php');
include_once('includes/network.php');
include_once('includes/data_forward.php');
include_once('includes/auth_conf.php');

// Look for translation in ./locale/$config['lang']/LC_MESSAGES/default.mo
putenv('LC_ALL=' . $config['lang']);
setlocale(LC_ALL, $config['lang']);
bindtextdomain('default', './locale');
textdomain('default');

$page = isset($_GET['page']) ? $_GET['page'] : 'system';

session_start();
if (empty($_SESSION['csrf_token'])) {
    if (function_exists('mcrypt_create_iv')) {
        $_SESSION['csrf_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
$csrf_token = $_SESSION['csrf_token'];

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>AirPi Configuration</title>

    <!-- Bootstrap Core CSS -->
    <link href="../inc/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
    <!-- MetisMenu CSS required by SB-Admin-2 -->
    <link href="../inc/metisMenu/metisMenu.min.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS SB-Admin-2 -->
    <link href="../inc/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet" type="text/css">
    <!-- Custom Fonts -->
    <link href="../inc/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- More Custom Styles -->
    <link href="style.css" rel="stylesheet" type="text/css">

  </head>
  <body>

    <div id="wrapper">
      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><?= my_html(_('AirPi Configuration')); ?></a>
        </div>
        <!-- /.navbar-header -->
        <!-- Navigation -->
        <div class="navbar-default sidebar" role="navigation">
          <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
              <li><a href="index.php?page=system"><i class="fa fa-cube fa-fw"></i> <?= my_html(_('System')); ?></a></li>
              <li><a href="index.php?page=network"><i class="fa fa-signal fa-fw"></i> <?= my_html(_('Internet')); ?></a></li>
              <li><a href="index.php?page=station"><i class="fa fa-tags fa-fw"></i> <?= my_html(_('Weather Station')); ?></a></li>
              <li><a href="index.php?page=data_forward"><i class="fa fa-database fa-fw"></i> <?= my_html(_('Data Forward')); ?></a></li>
              <li><a href="index.php?page=auth_conf"><i class="fa fa-lock fa-fw"></i> <?= my_html(_('Admin Password')); ?></a></li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.navbar-default -->
      </nav>
      <div id="page-wrapper">
        <!-- Page Heading -->
        <div class="row">
          <div class="col-lg-12">
            <p>
            <!-- <h1 class="page-header"><i class="fa fa-leaf"></i><?= THIS_APP; ?></h1> -->
          </div>
        </div><!-- /.row -->

<!-- Page Content -->
<?php
// Handle page actions.
switch($page) {
    case 'system':
        DisplaySystem();
        break;
    case 'station':
        DisplayStation();
        break;
    case 'network':
        DisplayNetwork();
        break;
    case 'data_forward':
        DisplayDataForward();
        break;
    case 'auth_conf':
        DisplayAuthConfig($config['admin_user'], $config['admin_pass']);
        break;
    default:
        $page = 'system';
        DisplaySystem();
}
?>
<!-- End Page Content -->

      </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->

    <!-- jQuery -->
    <script type="text/javascript" src="../inc/jquery/jquery-1.12.4.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script type="text/javascript" src="../inc/bootstrap/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript required by SB-Admin-2 -->
    <script type="text/javascript" src="../inc/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme SB-Admin-2 JavaScript -->
    <script type="text/javascript" src="../inc/sb-admin-2/js/sb-admin-2.min.js"></script>
<?php
// Add JavaScript for this specific page.
switch($page) {
    case 'network':
    case 'data_forward':
    case 'system':
        echo '    <!-- JavaScript for this page -->' . "\n";
        echo '    <script type="text/javascript" src="includes/' . $page . '.js"></script>' . "\n";
        break;
}
?>
  </body>
</html>
