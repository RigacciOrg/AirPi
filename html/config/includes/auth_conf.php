<?php

include_once( 'includes/status_messages.php' );

function DisplayAuthConfig($username, $password) {
    global $config;
    $status = new StatusMessages();
    if (isset($_POST['UpdateAdminPassword'])) {
        if (! CSRFValidate()) {
            error_log('CSRF violation');
        } else {
            if (password_verify($_POST['oldpass'], $password)) {
                $new_username = trim($_POST['username']);
                if ($_POST['newpass'] != $_POST['newpassagain']) {
                    $status->addMessage(_('New passwords do not match'), 'danger');
                } else if ($new_username == '') {
                    $status->addMessage(_('Username must not be empty'), 'danger');
                } else {
                    $config['admin_user'] = $new_username;
                    $config['admin_pass'] = password_hash($_POST['newpass'], PASSWORD_BCRYPT);
                    if (config_write()) {
                        $username = $new_username;
                        $status->addMessage(_('Admin password updated'));
                    } else {
                        $status->addMessage(_('Failed to update admin password'), 'danger');
                    }
                }
            } else {
                $status->addMessage(_('Old password does not match'), 'danger');
            }
        }
    }

?>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"><i class="fa fa-lock fa-fw"></i> <?= my_html(_('Admin Password')); ?></div>
        <div class="panel-body">
          <p><?php $status->showMessages(); ?></p>
          <form role="form" action="?page=auth_conf" method="POST">
            <?php CSRFToken() ?>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="username"><?= my_html(_('Username')); ?></label>
                <input type="text" class="form-control" name="username" value="<?= my_html($username); ?>"/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="password"><?= my_html(_('Old password')); ?></label>
                <input type="password" class="form-control" name="oldpass"/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="password"><?= my_html(_('New password')); ?></label>
                <input type="password" class="form-control" name="newpass"/>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-4">
                <label for="password"><?= my_html(_('Repeat new password')); ?></label>
                <input type="password" class="form-control" name="newpassagain"/>
              </div>
            </div>
            <input type="submit" class="btn btn-outline btn-primary" name="UpdateAdminPassword" value="<?= my_html(_('Save settings')); ?>" />
          </form>
        </div><!-- /.panel-body -->
      </div><!-- /.panel-default -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
<?php
}

?>
