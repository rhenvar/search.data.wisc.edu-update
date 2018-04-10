<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include_once 'specification.php';
include_once 'data_definition.php';
include_once 'query_builder.php';
include_once 'datalayer.php';

require "predis/autoload.php";
Predis\Autoloader::register();

if (isset($_GET['store_all_specifications'])) {

    header("Content-type: application/json");

    try {
        $redis = new Predis\Client(array(
            'host' => '10.128.127.156',
            'port' => 26379 
        ));

        $result = get_results('', 'dashboardsReports', 'relevance', '');
        usort($result, "cmp");
        $redis->set('all_specifications', json_encode($result));
        print json_encode('Redis specifications stored?: ' .  $redis->exists('all_specifications'));
        print json_encode('Number of specifications stored: ' . count($result));
        print json_encode($redis->get('all_specifications'));

    }

    catch (Exception $e) {
        print json_encode($e->getMessage());
    }
}
else if (isset($_GET['store_all_definitions'])) {

}
// TODO: Related dashboards by definition

function cmp($a, $b)
{
    return strcmp($a->specification_name, $b->specification_name);
}
?>
