<?php
if (!isset($station_id)) exit('Direct access denied');
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
                            <i class="fa fa-file-text-o fa-fw"></i> <?= my_html(_('CSV Export')) ?>
                        </div><!-- /.panel-heading -->
                        <div class="panel-body">
                            <p><?= my_html(_('Download of CSV file will start automatically by clicking the desired period. The operation can take a few minutes to complete; for example the extraction of an entire year produces a file of about 12 Mb and requires a processing time of about 2 minutes.')) ?></p>
                            <div id="airpi-download-panel">
                                <form action="download_csv.php" method="post" id="data-download">
                                <div class="form-group">

                                    <div class="row"><div class="col-lg-12">
                                        <label><?= my_html(_('Data Selection')) ?></label>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="t"     name="data[]" checked><i class="fa fa-thermometer fa-fw"></i><?= my_html(_('Temperature')) ?></label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="p"     name="data[]" checked><i class="rof rof-weather fa-fw"></i><?= my_html(_('Pressure')) ?></label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="hum"   name="data[]" checked><i class="fa fa-tint fa-fw"></i><?= my_html(_('Humidity')) ?></label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="pm10"  name="data[]" checked><i class="rof rof-skull fa-fw"></i><?= my_html(_('PM10 Concentration')) ?></label>
                                        </div>
                                        <!-- <div class="checkbox">
                                            <label><input type="checkbox" value="pm2.5" name="data[]" checked><i class="rof rof-skull fa-fw"></i><?= my_html(_('PM2.5 Concentration')) ?></label>
                                        </div> -->
                                    </div></div><!-- /.row -->

                                    <div class="row">
                                        <input type="hidden" name="id" value="<?= $station_id ?>">
                                        <input type="hidden" name="period" value="">
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading"><i class="fa fa-download fa-fw"></i> <?= my_html(_('Current Period')) ?></div>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        <a data-alias="curr_day"   href="#" class="list-group-item"><?= my_html(_('Day')) ?></a>
                                                        <a data-alias="curr_week"  href="#" class="list-group-item"><?= my_html(_('Week')) ?></a>
                                                        <a data-alias="curr_month" href="#" class="list-group-item"><?= my_html(_('Month')) ?></a>
                                                        <a data-alias="curr_year"  href="#" class="list-group-item"><?= my_html(_('Year')) ?></a>
                                                    </div>
                                                </div>
                                            </div><!-- /.panel -->
                                        </div><!-- /.col -->
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading"><i class="fa fa-download fa-fw"></i> <?= my_html(_('Previous Period')) ?></div>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        <a data-alias="prev_day"   href="#" class="list-group-item"><?= my_html(_('Day')) ?></a>
                                                        <a data-alias="prev_week"  href="#" class="list-group-item"><?= my_html(_('Week')) ?></a>
                                                        <a data-alias="prev_month" href="#" class="list-group-item"><?= my_html(_('Month')) ?></a>
                                                        <a data-alias="prev_year"  href="#" class="list-group-item"><?= my_html(_('Year')) ?></a>
                                                    </div>
                                                </div>
                                            </div><!-- /.panel -->
                                        </div><!-- /.col -->
                                    </div><!-- /.row -->
                                </div><!-- /.form-group -->
                                </form>
                            </div><!-- /#airpi-download-panel -->
                        </div><!-- /.panel-body -->
                    </div><!-- /.panel -->
                </div><!-- /.col-lg-8 -->
            </div><!-- /.row -->
