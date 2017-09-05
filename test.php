#!/usr/bin/php7.0
<?php require_once 'vendor/autoload.php';

$auth = new \SmtpFacade\SmtpAuthentication('test');
if ($auth->authenticate($argv[1], $argv[2])) {
	echo 'ok for '.$auth->getSessionLogin();
} else {
	echo 'fail';
}
$auth->logoff();
