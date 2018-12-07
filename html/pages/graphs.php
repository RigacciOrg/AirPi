<?php
if (!isset($station_id)) exit('Direct access denied');
$period = isset($_REQUEST['view']) ? $_REQUEST['view'] : 'weekly';
switch ($period) {
    case 'daily':
        $period_label = _('Daily');
        break;
    case 'monthly':
        $period_label = _('Monthly');
        break;
    case 'yearly':
        $period_label = _('Yearly');
        break;
    default:
        $period = 'weekly';
        $period_label = _('Weekly');
}
$graph_src = sprintf('rrd-graph.php?id=%s&amp;period=%s&amp;graph=', $station_id, $period);
$view_href = sprintf('?id=%s&amp;p=graphs&amp;view=', $station_id);
?>
            <div class="row">
                <div class="col-lg-12">
                    <p>
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
                                        <?= my_html(_('Change viewtype')) ?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu" id="period-select">
                                        <li><a data-alias="daily"   href="<?= $view_href ?>daily"><?= my_html(_('Daily')) ?></a></li>
                                        <li><a data-alias="weekly"  href="<?= $view_href ?>weekly"><?= my_html(_('Weekly')) ?></a></li>
                                        <li><a data-alias="monthly" href="<?= $view_href ?>monthly"><?= my_html(_('Monthly')) ?></a></li>
                                        <li><a data-alias="yearly"  href="<?= $view_href ?>yearly"><?= my_html(_('Yearly')) ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div><!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="airpi-graph-panel">
                                <img id="img-pm-concentration" alt="" class="img-responsive airpi-graph" src="<?= $graph_src ?>pm-concentration">
                                <img id="img-pressure"         alt="" class="img-responsive airpi-graph" src="<?= $graph_src ?>pressure">
                                <img id="img-temperature"      alt="" class="img-responsive airpi-graph" src="<?= $graph_src ?>temperature">
                                <img id="img-humidity"         alt="" class="img-responsive airpi-graph" src="<?= $graph_src ?>humidity">
                           <!-- <img id="img-pm-count"         alt="" class="img-responsive airpi-graph" src="<?= $graph_src ?>pm-count"> -->
                            </div>
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel -->
                </div><!-- /.col-lg-8 -->
            </div><!-- /.row -->
