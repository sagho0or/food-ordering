<?php
ini_set('display_errors', 1);
require 'vendor/autoload.php';

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$clientId = 'ARdFR2lfz-Mr06Ys18UfO778gN4ZRLenPbEjQO_MteNwBFk-OsKCqH_XFrlJVYN0jm9fpt7jqBP9kYQa';
$clientSecret = 'EIWU5N7hzF0_zxO9pue7RefjFrVbEL4z-HuU5FzNLrqAADCOG5f8XFDAhp6qaKuJFA7HBAe6vCDhcD4o';

$apiContext = new ApiContext(new OAuthTokenCredential($clientId, $clientSecret));
$apiContext->setConfig([
    'mode' => 'sandbox',
    'log.LogEnabled' => true,
    'log.FileName' => 'PayPal.log',
    'log.LogLevel' => 'DEBUG',
    'cache.enabled' => true,
]);

error_log('ApiContext is set up: ' . print_r($apiContext, true));
return $apiContext;
