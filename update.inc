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


try {
    $redis = new Predis\Client(array(
	'host' => 'localhost',
	'port' => 26379
    ));

    // Storing Specifications
    $result = get_results('', 'dashboardsReports', 'relevance', '', 'none');
    usort($result, "cmp");
    $redis->set('all_specifications', json_encode($result));
    $redis->persist('all_specifications');
    print json_encode('Redis specifications stored?: ' .  $redis->exists('all_specifications'));
    print json_encode('Number of specifications stored: ' . count($result));
    print json_encode($redis->get('all_specifications'));

    // Storing Definitions
    $result = get_results('', 'dataDefinitions', 'relevance', '', '');
    usort($result, "cmp_definitions");
    $redis->set('all_definitions', json_encode($result));
    $redis->persist('all_definitions');
    print json_encode('Redis definitions stored?: ' . $redis->exists('all_definitions'));
    print json_encode('Number of definitions stored: ' . count($result));
    print json_encode($redis->get('all_definitions'));

    // Storing Relations
    $result = get_all_relations();
    $redis->set('all_relations', json_encode($result));
    $redis->persist('all_relations');
    print json_encode('Redis relations stored?: ' . $redis->exists('all_relations'));
    print json_Encode($redis->get('all_relations'));

    // Storing Specification Functional Areas
    $results = get_specification_functional_areas();
    $functional_areas_set = array();
    foreach ($results as $result) {
	$areas = explode(", ", $result);
	foreach ($areas as $area) {
	    $functional_areas_set[$area] = 0;
	}
    }
    $keys = array_keys($functional_areas_set);
    $redis->set('all_specification_functional_areas', json_encode($keys));
    print(json_encode($keys));

    // Storing Definition Functional Areas 
    $results = get_definition_functional_areas();
    $functional_areas_set = array();
    foreach ($results as $result) {
	$areas = explode(",", $result);
	foreach ($areas as $area) {
	    $functional_areas_set[$area] = 0;
	}
    }
    $keys = array_keys($functional_areas_set);
    $redis->set('all_definition_functional_areas', json_encode($keys));
    print(json_encode($keys));

    // Storing Specification Types
    $results = get_specification_types();
    $redis->set('all_specification_types', json_encode($results));
    print(json_encode($results));

    // Storing Restrictions
    $specifications = json_decode($redis->get('all_specifications'));
    $restrictions = array();
    foreach ($specifications as $specification) {
	$restrictions[$specification->attribute_7_value] = null;
    }
    $results = array_keys($restrictions);
    $redis->set('all_restrictions', json_encode($results));
    print(json_encode($results));
}

catch (Exception $e) {
    print json_encode($e->getMessage());
}

function cmp($a, $b) {
    return strcmp($a->specification_name, $b->specification_name);
}

function cmp_definitions($a, $b) {
    return strcmp($a->definition_name, $b->definition_name);
}
?>
