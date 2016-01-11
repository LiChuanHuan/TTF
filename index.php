<?php

require 'vendor/autoload.php';

if (empty($_GET)) {
    include 'view/list.php';
}

if (isset($_GET['handle'])) {
    $handle = $_GET['handle'];
    include "handle/{$handle}Handle.php";
}
