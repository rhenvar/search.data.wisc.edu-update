<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require "predis/autoload.php";

Predis\Autoloader::register();

try {

    $redis = new Predis\Client(array(
        'host' => '10.128.127.156',
        'port' => 26379
    ));

    $redis->set("message", "IT WORKS");
    $message = $redis->get("message");

    echo $redis->get("message");
}

catch (Exception $e) {
    echo $e->getMessage();
}

?>
