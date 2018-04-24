<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include_once 'specification.php';
include_once 'data_definition.php';
include_once 'relation.php';
include_once 'query_builder.php';
include_once 'datalayer.php';

require "predis/autoload.php";
Predis\Autoloader::register();

if (isset($_GET['store_all_specifications'])) {

    header("Content-type: application/json");

    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));

        $result = get_results('', 'dashboardsReports', 'relevance', '');
        usort($result, "cmp");
        $redis->set('all_specifications', json_encode($result));
        $redis->persist('all_specifications');
        print json_encode('Redis specifications stored?: ' .  $redis->exists('all_specifications'));
        print json_encode('Number of specifications stored: ' . count($result));
        print json_encode($redis->get('all_specifications'));

    }

    catch (Exception $e) {
        print json_encode($e->getMessage());
    }
}
else if (isset($_GET['store_all_definitions'])) {
    header("Content-type: application/json");
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));

        $result = get_results('', 'dataDefinitions', 'relevance', '');
        usort($result, "cmp_definitions");
        $redis->set('all_definitions', json_encode($result));
        $redis->persist('all_definitions');
        print json_encode('Redis definitions stored?: ' . $redis->exists('all_definitions'));
        print json_encode('Number of definitions stored: ' . count($result));
        print json_encode($redis->get('all_definitions'));

    }
    catch (Exception $e) {
        print json_encode($e->getMessage());
    }
}

else if (isset($_GET['store_all_relations'])) {
    header("Content-type: application/json");
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));

        $result = get_all_relations();
        $redis->set('all_relations', json_encode($result));
        $redis->persist('all_relations');
        print json_encode('Redis relations stored?: ' . $redis->exists('all_relations'));
        print json_Encode($redis->get('all_relations'));
    }
    catch (Exception $e) {
        print json_encode($e->getMessage());
    }
}

function cmp($a, $b) {
    return strcmp($a->specification_name, $b->specification_name);
}

function cmp_definitions($a, $b) {
    return strcmp($a->definition_name, $b->definition_name);
}
?>
