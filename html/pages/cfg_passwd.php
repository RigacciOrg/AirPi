<?php
if (!isset($station_id)) exit('Direct access denied');
require_once 'cfg_functions.php';
require_once 'cfg_status_messages.php';
require_authenticated_user();
$csrf_token = csrf_session_token();
textdomain('cfg_default');

define('MIN_PASS_LEN', 6);
define('PASS_REGEX', '/^[\.0-9A-Za-z_-]{3,16}$/');

$status = new StatusMessages();
if (isset($_POST['UpdateAdminPassword'])) {
    // TODO: Add or substitute user? Only admin or other types of users?
    if (!csrf_validate()) exit('Invalid CSRF Token');
    $new_username = trim($_POST['username']);
    $new_password = $_POST['newpass'];
    $old_password = $_POST['oldpass'];
    $old_hash = $config['users'][$_SESSION['username']]['passwd'];
    if (password_verify($old_password, $old_hash)) {
        if (strlen($new_password) < MIN_PASS_LEN or $new_password != $_POST['newpassagain']) {
            $status->addMessage(_('New password invalid or mismatch'), 'danger');
        } else if (!preg_match(PASS_REGEX, $new_username)) {
            $status->addMessage(_('Invalid username'), 'danger');
        } else {
            // Remove all existing users and add this new one.
            $config['users'] = array();
            $config['users'][$new_username] = array();
            $config['users'][$new_username]['passwd'] = password_hash($new_password, PASSWORD_BCRYPT);
            $config['users'][$new_username]['isadmin'] = true;
            if (local_config_write()) {
                $_SESSION['username'] = $new_username;
                $status->addMessage(_('Admin password updated'));
            } else {
                $status->addMessage(_('Failed to update admin password'), 'danger');
            }
        }
    } else {
        $status->addMessage(_('Old password does not match'), 'danger');
    }
}
?>
    <div class="row">
        <div class="col-lg-6">
            <p><?= $status->showMessages(); ?></p>
        </div>
    </div><!-- /.row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><i class="fa fa-lock fa-fw"></i> <?= my_html(_('Admin Password')); ?></div>
                <div class="panel-body">
                    <form role="form" action="?p=cfg_passwd" method="post">
                        <?= csrf_hidden_input_token(); ?>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="username"><?= my_html(_('Username')); ?></label>
                                <input type="text" class="form-control" name="username" value="<?= my_html($_SESSION['username']); ?>"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="password"><?= my_html(_('Old password')); ?></label>
                                <input type="password" class="form-control" name="oldpass"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="password"><?= my_html(sprintf(_('New password (min %d chars)'), MIN_PASS_LEN)); ?></label>
                                <input type="password" class="form-control" name="newpass"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="password"><?= my_html(_('Repeat new password')); ?></label>
                                <input type="password" class="form-control" name="newpassagain"/>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-outline btn-primary" name="UpdateAdminPassword" value="<?= my_html(_('Save settings')); ?>" />
                    </form>
                </div><!-- /.panel-body -->
            </div><!-- /.panel-default -->
        </div><!-- /.col -->
    </div><!-- /.row -->
