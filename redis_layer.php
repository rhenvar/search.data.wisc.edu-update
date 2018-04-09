<?php

include_once 'specification.php';
include_once 'data_definition.php';
include_once 'query_builder.php';
include_once 'datalayer.php';

require "predis/autoload.php";
Predis\Autoloader::register();

function get_redis_specifications($search_input, $sort_by, $functional_area) {
    try {
         $redis = new Predis\Client(array(
            'host' => '10.128.127.156',
            'port' => 26379 
         ));
         if (!$redis->exists('all_specifications')) {
             // Load all specifications into Redis? 
         }

         $results = $redis->get('all_specifications');
         
         if (strcmp('', $search_input) == 0 && strcmp('', $functional_area)) {
             return json_decode($results);
         }
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function specifications_set() {
    try {
         $redis = new Predis\Client(array(
            'host' => '10.128.127.156',
            'port' => 26379 
         ));
         return $redis->exists('all_specifications'); 
    }
    catch (Exception $e) {
        return false;
    }
}
