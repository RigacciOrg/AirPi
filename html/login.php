<?php
require_once './functions.php';
require_once './config-init.php';
session_start();

// Translations are in ./locale/ directory.
putenv('LC_ALL=' . $config['lang']);
setlocale(LC_ALL, $config['lang']);
// We have two sets of messages: "default.po" and "cfg_default.po".
bindtextdomain('default', './locale');
bindtextdomain('cfg_default', './locale');
// The one to be used in this page.
textdomain('cfg_default');
// The ISO 639-1 language code (the first two chars).
$iso_639_1 = (strlen($config['lang']) >= 2) ? substr($config['lang'], 0, 2) : 'en';

?>
<!DOCTYPE html>
<html lang="<?= $iso_639_1 ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="Niccolo Rigacci <niccolo@rigacci.org>">
    <title><?= my_html($config['app_title']) ?></title>
    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="inc/bootstrap/css/bootstrap.min.css" type="text/css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="inc/sb-admin-2/css/sb-admin-2.min.css" type="text/css">
    <!-- Custom Fonts -->
    <link href="inc/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- CSS local override -->
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= my_html($config['app_title'] . ' - ' . _('Login')) ?></h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="post" action="login.php">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="<?= my_html(_('Username')) ?>" name="username" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="<?= my_html(_('Password')) ?>" name="password" type="password" value="">
                            </div>
                            <button class="btn btn-success btn-block" type="submit" value="login" name="login"><?= my_html(_('Login')) ?></button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
if(isset($_REQUEST['login'])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    if (array_key_exists($username, $config['users'])) {
        $hash = $config['users'][$username]['passwd'];
        if (password_verify($password, $hash)) {
            $_SESSION['username'] = $username;
            if (isset($_SESSION['req_p'])) {
                $open_url = './?p=' . urlencode($_SESSION['req_p']);
            } else {
                $open_url = './';
            }
            echo "<script>window.open('" . $open_url . "', '_self')</script>";
        } else {
            echo "<script>alert('Invalid username or password!')</script>";
        }
    }
}
?>
