<?php
// $station_id is defined by the including script.
$sensor = get_latest_data($station_id);
$pm10_icon = pm10_icon($sensor['pm10']);
$pressure_icon = pressure_icon($sensor['pressure']);
$tendency_icon = tendency_icon(pressure_diff_3h());

?>
            <div class="row">
              <p>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <i class="rof <?= $pm10_icon ?> rof-5x"></i>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <div class="huge"><?= my_sprintf('%d', $sensor['pm10']) ?></div>
                                    <div>PM10 &mu;g/m<sup>3</sup></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <i class="rof <?= $pressure_icon ?> rof-5x"></i> <i class="rof <?= $tendency_icon ?> rof-5x"></i>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <div class="huge"><?= my_sprintf('%d', $sensor['pressure']) ?></div>
                                    <div>Pressione hPa</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <i class="fa fa-thermometer fa-5x"></i>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <div class="huge"><?= my_sprintf('%.2f', $sensor['temperature']) ?></div>
                                    <div>Temperatura °C</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6">
                                    <i class="fa fa-tint fa-5x"></i>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <div class="huge"><?= my_sprintf('%.2f', $sensor['humidity']) ?></div>
                                    <div>Umidit&agrave; %</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-lg-10 col-md-12">
                    <div class="alert alert-info no-padding">
                        <div class="text-center no-margin standout-line"><i class="fa fa-clock-o fa-fw"></i> <?= date('H:i d/m/Y') ?></div>
                    </div>
                </div>
            </div><!-- /.row -->
