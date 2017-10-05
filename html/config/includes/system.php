<?php

require_once('includes/system_raspberrypi.php');

function DisplaySystem() {
    $status = new StatusMessages();
    list($memused, $memused_status) = sys_memused();
    list($cpuload, $cpuload_status) = sys_cpuload();
    list($device, $macaddress, $ipaddress, $netmask, $gateway, $signal) = sys_network();
    $opt_pending = read_options('options-pending');
    if (count($opt_pending) > 0) {
        $status->addMessage(sprintf(_('There are %d pending changes'), count($opt_pending)), 'danger');
    }
?>
  <div class="row">
    <div class="col-lg-12">
      <?= $status->showMessages(); ?>
    </div>
  </div>
  <div class="row">
  <div class="col-lg-12">
  <div class="panel panel-primary">
  <div class="panel-heading"><i class="fa fa-cube fa-fw"></i> <?= _('System') ?></div>
  <div class="panel-body">
<?php
    if (isset($_POST['system_reboot'])) {
        if (CSRFValidate()) {
            echo '<div class="alert alert-warning">' . my_html(_('System Rebooting Now!')) . '</div>';
            $result = shell_exec('sudo /sbin/shutdown -r now  > /dev/null &');
        }
    }
    if (isset($_POST['system_poweroff'])) {
        if (CSRFValidate()) {
            echo '<div class="alert alert-warning">' . my_html(_('System Powering Off Now!')) . '</div>';
            $result = shell_exec('sudo /sbin/poweroff  > /dev/null &');
        }
    }
    if (isset($_POST['system_apply_cfg'])) {
        if (CSRFValidate()) {
            echo '<div class="alert alert-warning">' . my_html(_('Applying Pending Config and Rebooting Now!')) . '</div>';
            if (save_pending() == TRUE) {
                if (apply_config() == 0) {
                    $result = shell_exec('/bin/sync');
                    sleep(1);
                    $result = shell_exec('sudo /sbin/shutdown -r now  > /dev/null &');
                }
            }
        }
    }
?>
    <div class="row">
    <div class="col-md-6">
    <div class="panel panel-default panel-red">
    <div class="panel-heading"><i class="fa fa-gears fa-fw"></i> <?= my_html(_('System Information')); ?></div>
    <div class="panel-body">
      <div class="info-item"><b><?= _('Hostname') ?></b></div> <?= my_html(sys_hostname()); ?><br>
      <div class="info-item"><b><?= _('Pi Revision') ?></b></div> <?= my_html(sys_RPiVersion()); ?><br>
      <div class="info-item"><b><?= _('Uptime') ?></b></div><?= my_html(sys_uptime()); ?><br><br>
      <div class="info-item"><b><?= _('Memory Used') ?></b></div>
        <div class="progress">
        <div class="progress-bar progress-bar-<?= $memused_status ?> progress-bar-striped active"
          role="progressbar"
          aria-valuenow="<?= $memused ?>" aria-valuemin="0" aria-valuemax="100"
          style="width: <?= $memused ?>%;"><?= $memused ?>%
        </div>
        </div>
      <div class="info-item"><b><?= _('CPU Load') ?></b></div>
        <div class="progress">
        <div class="progress-bar progress-bar-<?= $cpuload_status ?> progress-bar-striped active"
          role="progressbar"
          aria-valuenow="<?= $cpuload ?>" aria-valuemin="0" aria-valuemax="100"
          style="width: <?= $cpuload ?>%;"><?= $cpuload ?>%
        </div>
        </div>
    </div><!-- /.panel-body -->
    </div><!-- /.panel-default -->
    </div><!-- /.col-md-6 -->

    <div class="col-md-6">
    <div class="panel panel-default panel-green">
    <div class="panel-heading"><i class="fa fa-exchange fa-fw"></i> <?= my_html(_('Connection')); ?></div>
    <div class="panel-body">
      <div class="info-item"><b><?= my_html(_('Interface')); ?></b></div><?= my_html($device); ?><br>
      <div class="info-item"><b><?= my_html(_('MAC Address')); ?></b></div><?= my_html($macaddress); ?><br>
      <div class="info-item"><b><?= my_html(_('IP Address')); ?></b></div><?= my_html($ipaddress); ?><br>
      <div class="info-item"><b><?= my_html(_('Subnet Mask')); ?></b></div><?= my_html($netmask); ?><br>
      <div class="info-item"><b><?= my_html(_('Default Gateway')); ?></b></div><?= my_html($gateway); ?><br>
<?php if ($signal !== FALSE) { ?>
      <div class="info-item"><b><?= my_html(_('WiFi Signal Quality')); ?></b></div>
      <div class="progress">
        <div class="progress-bar progress-bar-info progress-bar-striped active"
          role="progressbar"
          aria-valuenow="<?= $signal ?>" aria-valuemin="0" aria-valuemax="100"
          style="width: <?= $signal ?>%;"><?= $signal ?>%
        </div>
      </div>
<?php } ?>
    </div><!-- /.panel-body -->
    </div><!-- /.panel-default -->
    </div><!-- /.col-md-6 -->
    </div><!-- /.row -->

    <form action="?page=system" id="action_form" method="POST">
      <?= CSRFToken() ?>
<?php if (count($opt_pending) > 0) { ?>
      <input type="button" class="btn btn-warning" id="apply_cfg_btn" name="apply_cfg_btn" data-toggle="modal" data-target="#confirm-dialog" value="<?= _('Apply Changes and Reboot') ?>" /><br><br>
<?php } ?>
      <input type="button" class="btn btn-warning" id="reboot_btn"    name="reboot_btn"    data-toggle="modal" data-target="#confirm-dialog" value="<?= _('Reboot') ?>" />
      <input type="button" class="btn btn-warning" id="poweroff_btn"  name="poweroff_btn"  data-toggle="modal" data-target="#confirm-dialog" value="<?= _('Poweroff') ?>" />
      <input type="submit" class="btn btn-outline btn-primary" value="<?= _('Refresh') ?>" />
      <input type="hidden" id="action" name="" value="" />
    </form>

  </div><!-- /.panel-body -->
  </div><!-- /.panel-primary -->
  </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->

<!-- Confirm modal dialog -->
<script type="text/javascript">
  var reboot_header    = <?= json_encode(_('Reboot Confirm')); ?>;
  var reboot_body      = <?= json_encode(_('The system will reboot with the current configuration (pending excluded).')); ?>;
  var poweroff_header  = <?= json_encode(_('Poweroff Confirm')); ?>;
  var poweroff_body    = <?= json_encode(_('WARNING: You will need manual intervention to power on the system again!')); ?>;
  var apply_cfg_header = <?= json_encode(_('Apply Config and Reboot Confirm')); ?>;
  var apply_cfg_body   = <?= json_encode(_('All the pending configuration will be applied, then the system will reboot.')); ?>;
</script>
<div class="modal fade" id="confirm-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header modal-header-danger"><span id="dlg_header"></span></div>
      <div class="modal-body"><span id="dlg_body"></span>
      <input type="hidden" id="dlg_action" name="dlg_action" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= my_html(_('Cancel')); ?></button>
        <a href="#" id="dlg_submit" class="btn btn-success success"><?= my_html(_('Submit')); ?></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End dialog -->

<?php
}
?>
