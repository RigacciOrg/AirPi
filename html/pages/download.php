<?php
// $station_id is defined by the including script.
?>
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Download dati</h3>
                </div><!-- /.col-lg-12 -->
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-file-text-o fa-fw"></i> Formato CSV
                        </div><!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="airpi-download-panel">
                                <form action="download_csv.php" method="post" id="data-download">
                                <div class="form-group">

                                    <div class="row"><div class="col-lg-12">
                                        <label>Seleziona dati</label>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="t"     name="data[]" checked><i class="fa fa-thermometer fa-fw"></i>Temperatura</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="p"     name="data[]" checked><i class="rof rof-weather fa-fw"></i>Pressione</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="hum"   name="data[]" checked><i class="fa fa-tint fa-fw"></i>Umidit&agrave;</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="pm10"  name="data[]" checked><i class="rof rof-skull fa-fw"></i>Concentrazione PM10</label>
                                        </div>
                                        <!-- <div class="checkbox">
                                            <label><input type="checkbox" value="pm2.5" name="data[]" checked><i class="rof rof-skull fa-fw"></i>Concentrazione PM2.5</label>
                                        </div> -->
                                    </div></div><!-- /.row -->

                                    <div class="row">
                                        <input type="hidden" name="id" value="<?= $station_id ?>">
                                        <input type="hidden" name="period" value="">
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading"><i class="fa fa-download fa-fw"></i> Periodo attuale</div>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        <a data-alias="curr_day"   href="#" class="list-group-item">Giorno</a>
                                                        <a data-alias="curr_week"  href="#" class="list-group-item">Settimana</a>
                                                        <a data-alias="curr_month" href="#" class="list-group-item">Mese</a>
                                                        <a data-alias="curr_year"  href="#" class="list-group-item">Anno</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.panel -->
                                        </div><!-- /.col -->
                                        <div class="col-lg-6">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading"><i class="fa fa-download fa-fw"></i> Periodo precedente</div>
                                                <div class="panel-body">
                                                    <div class="list-group">
                                                        <a data-alias="prev_day"  href="#" class="list-group-item">Giorno</a>
                                                        <a data-alias="prev_week"  href="#" class="list-group-item">Settimana</a>
                                                        <a data-alias="prev_month" href="#" class="list-group-item">Mese</a>
                                                        <a data-alias="prev_year"  href="#" class="list-group-item">Anno</a>
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
