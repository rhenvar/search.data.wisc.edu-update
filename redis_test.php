<?php

require "predis/autoload.php";

Predis\Autoloader::register();

try {
    $redis = new Predis\Client(array(
        "host" => "localhost",
        "port" => 26379
    ));

    echo $redis->get('all_specifications');
}

catch (Exception $e) {
    echo $e->getMessage();
}
?>
