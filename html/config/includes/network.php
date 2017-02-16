<?php

include_once( 'includes/status_messages.php' );

function DisplayNetwork() {
    $status = new StatusMessages();
    $opt_new = array();
    if (isset($_POST['UpdateNetworkConfig'])) {
        if (!CSRFValidate()) exit('Invalid CSRF Token');
        $opt_new['NET_USE_DHCP']       = @html_bool($_REQUEST['net_use_dhcp']);
        if ($opt_new['NET_USE_DHCP'] != 'Yes') {
            $opt_new['NET_IPADDRESS']  = is_ipv4($_REQUEST['net_ipaddress']);
            $opt_new['NET_NETMASK']    = is_netmask($_REQUEST['net_netmask']);
            $opt_new['NET_GATEWAY']    = is_ipv4($_REQUEST['net_gateway']);
            $opt_new['NET_DNS_SERVER'] = is_ipv4($_REQUEST['net_dns_server']);
        }
        $opt_new['NET_ALT_IPADDRESS']  = is_ipv4($_REQUEST['net_alt_ipaddress']);
        $opt_new['NET_ALT_NETMASK']    = is_netmask($_REQUEST['net_alt_netmask']);
        $opt_new['WIFI_ESSID1']        = $_REQUEST['wifi_essid1'];
        $opt_new['WIFI_ESSID2']        = $_REQUEST['wifi_essid2'];
        $opt_new['WIFI_ESSID3']        = $_REQUEST['wifi_essid3'];
        $opt_new['WIFI_PASSWORD1']     = $_REQUEST['wifi_password1'];
        $opt_new['WIFI_PASSWORD2']     = $_REQUEST['wifi_password2'];
        $opt_new['WIFI_PASSWORD3']     = $_REQUEST['wifi_password3'];

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
    $net_use_dhcp      = $opt['NET_USE_DHCP'];
    $net_ipaddress     = $opt['NET_IPADDRESS'];
    $net_netmask       = $opt['NET_NETMASK'];
    $net_gateway       = $opt['NET_GATEWAY'];
    $net_alt_ipaddress = $opt['NET_ALT_IPADDRESS'];
    $net_alt_netmask   = $opt['NET_ALT_NETMASK'];
    $net_dns_server    = $opt['NET_DNS_SERVER'];
    $wifi_essid1       = $opt['WIFI_ESSID1'];
    $wifi_essid2       = $opt['WIFI_ESSID2'];
    $wifi_essid3       = $opt['WIFI_ESSID3'];
    $wifi_password1    = $opt['WIFI_PASSWORD1'];
    $wifi_password2    = $opt['WIFI_PASSWORD2'];
    $wifi_password3    = $opt['WIFI_PASSWORD3'];

?>
  <div class="row">
    <div class="col-lg-12">
      <?= $status->showMessages(); ?>
    </div>
  </div><!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-signal fa-fw"></i> <?= my_html(_('Network Configuration')); ?></div>
        <div class="panel-body">
          <form role="form" action="?page=network" method="POST">
            <?= CSRFToken() ?>
            <div class="row">

              <div class="col-md-6">
                <div class="panel panel-default panel-yellow">
                  <div class="panel-heading"><i class="fa fa-sitemap fa-fw"></i> <?= my_html(_('Wired Interface')); ?> (eth0)</div>
                  <div class="panel-body">

                    <div class="row">
                      <div class="checkbox col-md-12">
                        <label><input type="checkbox" id="net_use_dhcp" name="net_use_dhcp"<?= ($net_use_dhcp == 'Yes') ? ' checked' : '' ?>>
                        <?= my_html(_('Automatic by DHCP')); ?></label>
                      </div>
                    </div>

                    <fieldset id="manualconfig">
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="net_ipaddress"><?= my_html(_('IP Address')); ?></label>
                          <input type="text" class="form-control" name="net_ipaddress" value="<?= my_html($net_ipaddress); ?>"/>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="net_netmask"><?= my_html(_('Subnet Mask')); ?></label>
                          <input type="text" class="form-control" name="net_netmask" value="<?= my_html($net_netmask); ?>"/>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label for="net_gateway"><?= my_html(_('Default Gateway')); ?></label>
                          <input type="text" class="form-control" name="net_gateway" value="<?= my_html($net_gateway); ?>"/>
                        </div>
                        <div class="form-group col-md-6">
                          <label for="net_dns_server"><?= my_html(_('DNS Server')); ?></label>
                          <input type="text" class="form-control" name="net_dns_server" value="<?= my_html($net_dns_server); ?>"/>
                        </div>
                      </div>
                    </fieldset>

                    <h4><?= my_html(_('Alternative Address')); ?> (eth0:0)</h4>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="net_alt_ipaddress"><?= my_html(_('IP Address')); ?></label>
                        <input type="text" class="form-control" name="net_alt_ipaddress" value="<?= my_html($net_alt_ipaddress); ?>"/>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="net_alt_netmask"><?= my_html(_('Subnet Mask')); ?></label>
                        <input type="text" class="form-control" name="net_alt_netmask" value="<?= my_html($net_alt_netmask); ?>"/>
                      </div>
                    </div>

                  </div><!-- /.panel-body -->
                </div><!-- /.panel-default -->
              </div><!-- /.col-md-6 -->

              <div class="col-md-6">
                <div class="panel panel-default panel-green">
                  <div class="panel-heading"><i class="fa fa-wifi fa-fw"></i> <?= my_html(_('WiFi Interface')); ?> (wlan0)</div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="wifi_essid1"><?= my_html(_('ESSID')) . ' #1' ?></label>
                        <input type="text" class="form-control" name="wifi_essid1" value="<?= my_html($wifi_essid1); ?>"/>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="wifi_password1"><?= my_html(_('Password')) . ' #1' ?></label>
                        <input type="password" class="form-control toggle_show" id="wifi_password1" name="wifi_password1" value="<?= my_html($wifi_password1); ?>"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="wifi_essid2"><?= my_html(_('ESSID')) . ' #2' ?></label>
                        <input type="text" class="form-control" name="wifi_essid2" value="<?= my_html($wifi_essid2); ?>"/>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="wifi_password2"><?= my_html(_('Password')) . ' #2' ?></label>
                        <input type="password" class="form-control toggle_show" id="wifi_password2" name="wifi_password2" value="<?= my_html($wifi_password2); ?>"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-md-6">
                        <label for="wifi_essid3"><?= my_html(_('ESSID')) . ' #3' ?></label>
                        <input type="text" class="form-control" name="wifi_essid3" value="<?= my_html($wifi_essid3); ?>"/>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="wifi_password3"><?= my_html(_('Password')) . ' #3' ?></label>
                        <input type="password" class="form-control toggle_show" id="wifi_password3" name="wifi_password3" value="<?= my_html($wifi_password3); ?>"/>
                      </div>
                    </div>
                    <div class="row">
                      <div class="checkbox col-md-12">
                        <label><input type="checkbox" id="showpasswd" name="showpasswd">
                        <?= my_html(_('Show password')); ?></label>
                      </div>
                    </div>
                  </div><!-- /.panel-body -->
                </div><!-- /.panel-default -->
              </div><!-- /.col-md-6 -->

            </div><!-- /.row -->
            <input type="submit" class="btn btn-outline btn-primary" name="UpdateNetworkConfig" value="<?= my_html(_('Save settings')); ?>" />
          </form>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
<?php
}
?>
