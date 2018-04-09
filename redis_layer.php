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
         
         // Default Landing
         if (strcmp('', $search_input) == 0) {
             return json_decode($results);
         }
         else {
             if (strcmp('dates', $sort_by) == 0) {

             }
             if (strcmp('', $search_input) != 0 && strcmp('relevance', $sort_by) == 0) {
                 $filtered_array = array();
                 $results = json_decode($results);

                 $input_array = explode(' ', $search_input);
                 foreach ($results as $result) {
                     $specification_array = explode(' ', $result->specification_name);
                     $specification_word_count = count($specification_array);
                     $word_occurrences = 0;

                     foreach ($input_array as $key_word) {
                         foreach ($specification_array as $compare_word) {
                             $distance = levenshtein(strtolower($key_word), strtolower($compare_word));
                             if ($distance <= 1 && $distance >= -1) {
                                 $word_occurrences++;
                             }
                         }
                     }
                     if ($word_occurrences > 0) {
                         $ratio = $word_occurrences / (float) $specification_word_count;
                         $result->ratio = $ratio;
                         array_push($filtered_array, $result);
                     }
                 }
                 usort($filtered_array, "cmp_ratio");
                 return $filtered_array;
             }
         }
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function cmp_ratio($a, $b) {
    $a_ratio = $a->ratio;
    $b_ratio = $b->ratio;
    if ($a_ratio == $b_ratio) {
        return 0;
    }
    return ($a_ratio < $b_ratio) ? 1 : -1;
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
