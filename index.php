<?php

require 'vendor/autoload.php';

define('OUT_FOLDER',dirname(__FILE__).DIRECTORY_SEPARATOR.'Out'.DIRECTORY_SEPARATOR);

if (empty($_GET)) {
    include 'view/list.php';
}

if (isset($_GET['handle'])) {
    $handle = $_GET['handle'];
    include "handle/{$handle}Handle.php";
}

