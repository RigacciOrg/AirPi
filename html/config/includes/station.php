<?php

include_once( 'includes/status_messages.php' );

function DisplayStation() {
    $status = new StatusMessages();
    $opt_new = array();
    if (isset($_POST['UpdateStationConfig'])) {
        if (!CSRFValidate()) exit('Invalid CSRF Token');
        $opt_new['STA_NAME']       = $_REQUEST['sta_name'];
        $opt_new['STA_LAT']        = $_REQUEST['sta_lat'];
        $opt_new['STA_LON']        = $_REQUEST['sta_lon'];
        $opt_new['STA_ELE']        = $_REQUEST['sta_ele'];
        $opt_new['PMS_SN']         = $_REQUEST['pms_sn'];
        $opt_new['PMS5003_SERIAL'] = $_REQUEST['pms5003_serial'];
        $opt_new['BME280_I2C']     = $_REQUEST['bme280_i2c'];

        // Check options validity.
        $opt_regexp = read_options('options-regexp');
        $invalid_fields = array();
        foreach ($opt_new as $key => $val) {
            if ($val === FALSE) {
                array_push($invalid_fields, $key);
                // Invalid field, show it anyway in the form.
                $opt_new[$key] = $_REQUEST[strtolower($key)];
            } else {
                if (array_key_exists($key, $opt_regexp)) {
                    if (! is_regexp($opt_new[$key], $opt_regexp[$key])) {
                        array_push($invalid_fields, $key);
                    }
                }
            }
        }

        // Show error message if there are invalid fields, or save new pending options.
        if (count($invalid_fields) > 0) {
            $msg = sprintf(_('There are errors, check: %s'), implode(', ', $invalid_fields));
            $status->addMessage($msg, 'danger');
        } else {
            $saved = merge_pending($opt_new);
            if ($saved === FALSE) {
                $status->addMessage(_('Error: Cannot save new options'), 'danger');
            } else {
                $status->addMessage(sprintf(_('Options saved. There are %d changes pending for application'), $saved), 'success');
            }
        }
    }

    $opt = read_options('options');
    $opt_pending = read_options('options-pending');
    if (count($opt_pending) > 0) {
        $status->addMessage(sprintf(_('There are %d pending changes'), count($opt_pending)), 'danger');
    }
    $opt = array_merge($opt, $opt_pending, $opt_new);
    $sta_name       = $opt['STA_NAME'];
    $sta_lat        = $opt['STA_LAT'];
    $sta_lon        = $opt['STA_LON'];
    $sta_ele        = $opt['STA_ELE'];
    $pms_sn         = $opt['PMS_SN'];
    $pms5003_serial = $opt['PMS5003_SERIAL'];
    $bme280_i2c     = $opt['BME280_I2C'];

?>
  <form role="form" action="?page=station" method="POST">
  <?= CSRFToken() ?>
  <div class="row">
    <div class="col-lg-6">
      <?= $status->showMessages(); ?>
    </div>
  </div><!-- /.row -->
  <div class="row">
    <div class="col-lg-6">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-tags fa-fw"></i> <?= my_html(_('Weather Station')); ?></div>
        <div class="panel-body">
          <div class="row">
            <div class="form-group col-md-12">
              <label for="sta_name"><?= my_html(_('Station Name')); ?></label>
              <input type="text" class="form-control" name="sta_name" value="<?= my_html($sta_name); ?>"/>
            </div>
            <div class="form-group col-md-4">
              <label for="sta_lat"><?= my_html(_('Latitude')); ?></label>
              <input type="text" class="form-control" name="sta_lat" value="<?= my_html($sta_lat); ?>"/>
            </div>
            <div class="form-group col-md-4">
              <label for="sta_lon"><?= my_html(_('Longitude')); ?></label>
              <input type="text" class="form-control" name="sta_lon" value="<?= my_html($sta_lon); ?>"/>
            </div>
            <div class="form-group col-md-4">
              <label for="sta_ele"><?= my_html(_('Elevation')); ?></label>
              <input type="text" class="form-control" name="sta_ele" value="<?= my_html($sta_ele); ?>"/>
            </div>
          </div><!-- /.row -->
          <div class="row">
            <div class="form-group col-md-6">
              <label for="pms_sn"><?= my_html(_('PM Sensor ID')); ?></label>
              <input type="text" class="form-control" name="pms_sn" value="<?= my_html($pms_sn); ?>"/>
            </div>
          </div><!-- /.row -->
          <div class="row">
            <div class="form-group col-md-6">
              <label for="pms5003_serial"><?= my_html(_('PM Serial Device')); ?></label>
              <input type="text" class="form-control" name="pms5003_serial" value="<?= my_html($pms5003_serial); ?>"/>
            </div>
            <div class="form-group col-md-6">
              <label for="bme280_i2c"><?= my_html(_('BME280 I2C Address')); ?></label>
              <input type="text" class="form-control" name="bme280_i2c" value="<?= my_html($bme280_i2c); ?>"/>
            </div>
          </div><!-- /.row -->
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-6 -->
  </div><!-- /.row -->
  <div class="row">
    <div class="col-lg-6">
      <input type="submit" class="btn btn-outline btn-primary" name="UpdateStationConfig" value="<?= my_html(_('Save settings')); ?>" />
    </div><!-- /.col-lg-6 -->
  </div><!-- /.row -->
  </form>
<?php
}
?>
