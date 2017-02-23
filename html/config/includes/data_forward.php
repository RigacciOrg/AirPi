<?php

include_once( 'includes/status_messages.php' );

function DisplayDataForward() {
    $status = new StatusMessages();
    $opt_new = array();
    if (isset($_POST['UpdateDataForwardConfig'])) {
        if (!CSRFValidate()) exit('Invalid CSRF Token');
        $opt_new['HTTP_REQUEST_URL'] = $_REQUEST['http_request_url'];
        $opt_new['HTTP_LOGIN']       = $_REQUEST['http_login'];
        $opt_new['HTTP_PASSWORD']    = $_REQUEST['http_password'];
        $opt_new['MQTT_HOST']        = $_REQUEST['mqtt_host'];
        $opt_new['MQTT_LOGIN']       = $_REQUEST['mqtt_login'];
        $opt_new['MQTT_PASSWORD']    = $_REQUEST['mqtt_password'];

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
    $http_request_url = $opt['HTTP_REQUEST_URL'];
    $http_login       = $opt['HTTP_LOGIN'];
    $http_password    = $opt['HTTP_PASSWORD'];
    $mqtt_host        = $opt['MQTT_HOST'];
    $mqtt_login       = $opt['MQTT_LOGIN'];
    $mqtt_password    = $opt['MQTT_PASSWORD'];

?>
  <div class="row">
    <div class="col-lg-12">
      <?= $status->showMessages(); ?>
    </div>
  </div><!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-database fa-fw"></i> <?= my_html(_('Data Forward')); ?></div>
        <div class="panel-body">
          <form role="form" action="?page=data_forward" method="POST">
            <?= CSRFToken() ?>
            <div class="row">

              <div class="col-md-6">
                <div class="panel panel-default panel-green">
                  <div class="panel-heading"><i class="fa fa-feed fa-fw"></i> <?= my_html(_('HTTP Protocol')); ?></div>
                  <div class="panel-body">

                    <div class="row">
                      <div class="form-group col-md-12">
                        <label for="net_ipaddress"><?= my_html(_('URL')); ?></label>
                        <input type="text" class="form-control" name="http_request_url" value="<?= my_html($http_request_url); ?>"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="net_gateway"><?= my_html(_('Login')); ?></label>
                        <input type="text" class="form-control" name="http_login" value="<?= my_html($http_login); ?>"/>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="net_dns_server"><?= my_html(_('Password')); ?></label>
                        <input type="password" class="form-control toggle_show" name="http_password" value="<?= my_html($http_password); ?>"/>
                      </div>
                    </div>

                  </div><!-- /.panel-body -->
                </div><!-- /.panel-default -->
              </div><!-- /.col-md-6 -->

              <div class="col-md-6">
                <div class="panel panel-default panel-green">
                  <div class="panel-heading"><i class="fa fa-feed fa-fw"></i> <?= my_html(_('MQTT Protocol')); ?></div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="form-group col-md-12">
                        <label for="wifi_essid1"><?= my_html(_('Host')) ?></label>
                        <input type="text" class="form-control" name="mqtt_host" value="<?= my_html($mqtt_host); ?>"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="wifi_essid2"><?= my_html(_('Login')) ?></label>
                        <input type="text" class="form-control" name="mqtt_login" value="<?= my_html($mqtt_login); ?>"/>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="wifi_password2"><?= my_html(_('Password')) ?></label>
                        <input type="password" class="form-control toggle_show" name="mqtt_password" value="<?= my_html($mqtt_password); ?>"/>
                      </div>
                    </div>
                  </div><!-- /.panel-body -->
                </div><!-- /.panel-default -->
              </div><!-- /.col-md-6 -->
            </div><!-- /.row -->

            <div class="row">
              <div class="checkbox col-md-12">
                <label><input type="checkbox" id="showpasswd" name="showpasswd">
                <?= my_html(_('Show password')); ?></label>
              </div>
            </div><!-- /.row -->

            <input type="submit" class="btn btn-outline btn-primary" name="UpdateDataForwardConfig" value="<?= my_html(_('Save settings')); ?>" />
          </form>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
<?php
}
?>
