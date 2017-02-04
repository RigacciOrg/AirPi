<?php

require_once('functions.php');
$period = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'weekly';
switch ($period) {
    case 'daily':
        $period_label = 'Giornaliera';
        break;
    case 'monthly':
        $period_label = 'Mensile';
        break;
    case 'yearly':
        $period_label = 'Annuale';
        break;
    default:
        $period = 'weekly';
        $period_label = 'Settimanale';
}

?>
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Grafici</h3>
                </div><!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-area-chart fa-fw"></i> <span id="period-label"><?= my_html($period_label) ?></span>
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Cambia vista
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu" id="period-select">
                                        <li><a data-alias="daily"   href="?page=graphs&view=daily">Giornaliera</a></li>
                                        <li><a data-alias="weekly"  href="?page=graphs&view=weekly">Settimanale</a></li>
                                        <li><a data-alias="monthly" href="?page=graphs&view=monthly">Mensile</a></li>
                                        <li><a data-alias="yearly"  href="?page=graphs&view=yearly">Annuale</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="airpi-graph-panel">
                                <img id="img-pm-concentration" alt="" class="img-responsive airpi-graph" src="rrd-graph.php?graph=pm-concentration&period=<?= $period ?>">
                                <img id="img-pressure"         alt="" class="img-responsive airpi-graph" src="rrd-graph.php?graph=pressure&period=<?= $period ?>">
                                <img id="img-temperature"      alt="" class="img-responsive airpi-graph" src="rrd-graph.php?graph=temperature&period=<?= $period ?>">
                                <img id="img-humidity"         alt="" class="img-responsive airpi-graph" src="rrd-graph.php?graph=humidity&period=<?= $period ?>">
                           <!-- <img id="img-pm-count"         alt="" class="img-responsive airpi-graph" src="rrd-graph.php?graph=pm-count&period=<?= $period ?>"> -->
                            </div>
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel -->
                </div><!-- /.col-lg-8 -->
            </div><!-- /.row -->
