<?php
if (!isset($station_id)) exit('Direct access denied');
?>
            <div class="row">
                <p>
            </div><!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-calendar fa-fw"></i> <?= my_html(_('Exceeding PM10 Calendar')) ?>
                        </div><!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="calendar" station-id="<?= $station_id ?>"></div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
