<?php
require_once('functions.php');
// $station_id is now defined.
$refresh = FALSE;
$page = isset($_GET['page']) ? $_GET['page'] : NULL;
switch ($page) {
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
<html lang="it">
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
    <title><?= my_html(APP_TITLE) ?></title>

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
                <a class="navbar-brand" href="./"><i class="fa fa-envira fa-fw"></i> <?= my_html(STATION_NAME) ?></a>
            </div><!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li><a href="?id=<?= $station_id ?>&amp;page=dashboard"><i class="fa fa-dashboard fa-fw"></i> Display</a></li>
                        <li><a href="?id=<?= $station_id ?>&amp;page=graphs"><i class="fa fa-area-chart fa-fw"></i> Grafici</a></li>
                        <li><a href="?id=<?= $station_id ?>&amp;page=calendar"><i class="fa fa-calendar fa-fw"></i> Calendario</a></li>
                        <li><a href="?id=<?= $station_id ?>&amp;page=download"><i class="fa fa-download fa-fw"></i> Download dati</a></li>
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
?>
    <script src="inc/airpi.js"></script>

</body>
</html>
