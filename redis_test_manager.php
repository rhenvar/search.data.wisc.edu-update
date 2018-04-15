<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require "predis/autoload.php";

Predis\Autoloader::register();

try {
    $output = array();
    exec('ps -e -o command', $output);

    //$redis = new Predis\Client(array(
    //    //'host' => '10.128.127.156', // wwwtestsearch.data.wisc.edu
    //    'host' => '128.104.80.118',
    //    'port' => 26379,
    //    'timeout' => 10));
    ////$redis = new Predis\Client(array('password' => 'ra2FAECYupAmuqxD', 'port'));

    //$redis->set("message", "IT WORKS");
    //$message = $redis->get("message");
    //echo $redis->get("message");
    echo(implode($output));
}

catch (Exception $e) {
    echo $e->getMessage();
}
?>
