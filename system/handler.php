<?php

Error_Reporting(E_ALL);
date_default_timezone_set('Europe/Samara');

$_root = sprintf('%s/', rtrim($GLOBALS['_SERVER']['DOCUMENT_ROOT'], '/'));

define('ROOT_DIR', $_root);
define('LIB_DIR', $_root . 'lib/');

require(LIB_DIR . 'system/consts.php');
require(LIB_DIR . 'system/debug.php');
require(LIB_DIR . 'services/functions.php');
require(LIB_DIR . 'system/kernel.php');

set_magic_quotes_runtime(0);
if (get_magic_quotes_gpc())
{
    magicQuotesSuck($_POST);
    magicQuotesSuck($_GET);
    magicQuotesSuck($_COOKIE);
    magicQuotesSuck($_SERVER);
    magicQuotesSuck($_REQUEST);
}


session_name('ow');
session_start();
$_expiry = 60 * 60 * 24 * 14;
setcookie(session_name(), session_id(), (time() + $_expiry), "/");

$Kernel = new CKernel();
$Kernel->Init();
$Error->SetReportingMode(DISPLAY_ERROR);
$Kernel->Execute($GLOBALS['_SERVER']['SCRIPT_NAME']);
