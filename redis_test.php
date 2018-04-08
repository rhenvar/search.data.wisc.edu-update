<?php

require "predis/autoload.php";

Predis\Autoloader::register();

try {
    $redis = new Predis\Client(array(
        "scheme" => "tcp",
        "host" => "10.128.127.156",
        "port" => 26379
        //"password" => "ra2FAECYupAmuqxD" 
    ));

    echo $redis->exists('message') ? $redis->get('message') : "No value under key message";

}

catch (Exception $e) {
    echo $e->getMessage();
}
?>
