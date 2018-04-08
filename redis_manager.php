<?php

require "predis/autoload.php";
Predis\Autoloader::register();

try {
    $redis = new Predis\Client(array(
        'host' => '10.128.127.156',
        'port' => 26379 
    ));
    
    echo $redis->get('message');
}

catch (Exception $e) {
    echo $e->getMessage();
}
?>
