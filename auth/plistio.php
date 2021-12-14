<?php 
require(__DIR__.'/vendor/autoload.php');
require(__DIR__.'/plistio-config.php');
require(__DIR__.'/includes/plistio-setup.php');
require(__DIR__.'/includes/plistio-functions.php');
$plistio_sql = new mysqli(PLISTIO_HOST, PLISTIO_DB_USER, PLISTIO_DB_PASS, PLISTIO_DB);