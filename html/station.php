<?php
ob_start ();
session_start();
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['username']);
    header('Location: ./');
    exit;
}
require_once './functions.php';
require_once './config-init.php';
$station_id = requested_station_id();
$station_name = requested_station_name($station_id);

// Translations are in ./locale/ directory.
putenv('LC_ALL=' . $config['lang']);
setlocale(LC_ALL, $config['lang']);
// We have two sets of messages: "default.po" and "cfg_default.po".
bindtextdomain('default', './locale');
bindtextdomain('cfg_default', './locale');
// The one to be used in this page.
textdomain('default');
// The ISO 639-1 language code (the first two chars).
$iso_639_1 = (strlen($config['lang']) >= 2) ? substr($config['lang'], 0, 2) : 'en';

$refresh = FALSE;
$page = isset($_GET['p']) ? $_GET['p'] : NULL;
switch ($page) {
    case 'cfg_data_forward':
    case 'cfg_network':
    case 'cfg_passwd':
    case 'cfg_station':
    case 'cfg_system':
        break;
    case 'graphs':
        $refresh = 600;
        break;
    case 'download':
    case 'calendar':
        break;
    default:
        $refresh = 60;
        $page = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="<?= $iso_639_1 ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Niccolo Rigacci <niccolo@rigacci.org>">
    <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="icons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="icons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="icons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">
<?php
if ($refresh) echo '    <meta http-equiv="refresh" content="' . $refresh . '">' . "\n";
?>
    <title><?= my_html($config['app_title']) ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="inc/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- MetisMenu CSS -->
    <link href="inc/metisMenu/metisMenu.min.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="inc/sb-admin-2/css/sb-admin-2.min.css" rel="stylesheet" type="text/css">
    <!-- Custom Fonts -->
    <link href="inc/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="inc/fonts/rigacci.org-webfont.css" rel="stylesheet" type="text/css">
<?php
if ($page == 'calendar') echo '    <link href="inc/cal/bootstrap-year-calendar.min.css" rel="stylesheet" type="text/css">' . "\n";
?>
    <!-- Local CSS overrides -->
    <link href="inc/cfg_style.css" rel="stylesheet" type="text/css">
    <link href="inc/airpi.css" rel="stylesheet" type="text/css">

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
                <a class="navbar-brand" href="./"><i class="fa fa-envira fa-fw"></i> <?= my_html($station_name) ?></a>
            </div><!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li><a href="?id=<?= $station_id ?>&amp;p=dashboard"><i class="fa fa-dashboard fa-fw"></i> <?= my_html(_('Display')) ?></a></li>
                        <li><a href="?id=<?= $station_id ?>&amp;p=graphs"><i class="fa fa-area-chart fa-fw"></i> <?= my_html(_('Graphs')) ?></a></li>
                        <li><a href="?id=<?= $station_id ?>&amp;p=calendar"><i class="fa fa-calendar fa-fw"></i> <?= my_html(_('Calendar')) ?></a></li>
                        <li><a href="?id=<?= $station_id ?>&amp;p=download"><i class="fa fa-download fa-fw"></i> <?= my_html(_('Data Download')) ?></a></li>
                        <li><a href="#"><i class="fa fa-wrench fa-fw"></i> <?= my_html(_('Configuration')) ?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="?p=cfg_passwd"><i class="fa fa-lock fa-fw"></i> <?= my_html(_('Admin Password')); ?></a></li>
                                <li><a href="?p=cfg_system"><i class="fa fa-cube fa-fw"></i> <?= my_html(_('System')); ?></a></li>
                                <li><a href="?p=cfg_network"><i class="fa fa-signal fa-fw"></i> <?= my_html(_('Internet')); ?></a></li>
                                <li><a href="?p=cfg_station"><i class="fa fa-tags fa-fw"></i> <?= my_html(_('Weather Station')); ?></a></li>
                                <li><a href="?p=cfg_data_forward"><i class="fa fa-database fa-fw"></i> <?= my_html(_('Data Forward')); ?></a></li>
                                <li><a href="?logout=yes"><i class="fa fa-sign-out fa-fw"></i> <?= my_html(_('Logout')) ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.sidebar-collapse -->
            </div><!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
<?php
include('pages' . DIRECTORY_SEPARATOR . $page . '.php');
?>
        </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->

    <!-- jQuery -->
    <script type="text/javascript" src="inc/jquery/jquery-1.12.4.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script type="text/javascript" src="inc/bootstrap/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript required by SB-Admin-2 -->
    <script type="text/javascript" src="inc/metisMenu/metisMenu.min.js"></script>
    <!-- Custom Theme SB-Admin-2 JavaScript -->
    <script type="text/javascript" src="inc/sb-admin-2/js/sb-admin-2.min.js"></script>
<?php
if ($page == 'calendar') {
    echo '    <script src="inc/cal/bootstrap-year-calendar.min.js"></script>' . "\n";
    echo '    <script src="inc/cal/bootstrap-year-calendar.it.js" charset="UTF-8"></script>' . "\n";
}
if (file_exists("pages/${page}.js")) {
    echo '    <!-- Custom JavaScript for this page -->' . "\n";
    echo '    <script src="pages/' . $page . '.js"></script>' . "\n";
}
?>
    <script src="inc/airpi.js"></script>

</body>
</html>
<?php ob_end_flush(); ?>
