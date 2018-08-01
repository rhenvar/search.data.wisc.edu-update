<?php

include_once 'specification.php';
include_once 'data_definition.php';
include_once 'relation.php';
include_once 'query_builder.php';
include_once 'datalayer.php';

require "predis/autoload.php";
Predis\Autoloader::register();


function get_redis_specifications($search_input, $sort_by, $functional_area, $specification_type, $access_restrictions) {
    $functional_areas_table = [
        "all" => "",
        "uwmadison" => "University of Wisconsin - Madison",
        "cirriculuminstruction" => "Curriculum & Instruction",
        "facultystaff" => "Faculty & Staff",
        "finance" => "Finance",
        "research" => "Research",
        "students" => "Students"
    ];
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_specifications')) {
            // Load all specifications into Redis?
        }

        $results = json_decode($redis->get('all_specifications'));

        // Default Landing
        // Still need to check for functional areas
        if (strcmp('', $search_input) == 0 && strcmp('relevance', $sort_by) == 0) {
            $filtered_array = array();
            foreach ($results as $result) {
                if (contains_area($result->functional_areas, $functional_area) && contains_area($result->specification_type, $specification_type) && contains_area($result->attribute_7_value, $access_restrictions)) {
                    array_push($filtered_array, $result);
                }
            }
            return $filtered_array;
        }
        else {

	    //*** Needs to work in tandem with input_filter
            if (strcmp('', $search_input) == 0 && strcmp('dates', $sort_by) == 0) {
                $filtered_array = array();
                foreach ($results as $result) {
                    if (contains_area($result->functional_areas, $functional_area) && contains_area($result->specification_type, $specification_type) && contains_area($result->attribute_7_value, $access_restrictions)) {
                        array_push($filtered_array, $result);
                    }
                }
                usort($filtered_array, "cmp_date");
                return $filtered_array;
            }
	    //***

            if (strcmp('', $search_input) != 0 /*&& strcmp('relevance', $sort_by) == 0*/) {
                $filtered_array = array();

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
                    if ($word_occurrences > 0 && contains_area($result->functional_areas, $functional_area) && contains_area($result->specification_type, $specification_type) && contains_area($result->attribute_7_value, $access_restrictions)) {
                        $ratio = $word_occurrences / (float) $specification_word_count;
                        $result->ratio = $ratio;
                        array_push($filtered_array, $result);
                    }
                }
		// IDEA: Sort by cmp_date if $sort_by == newest first, cmp_ratio if sort_by == relevance
		if (strcmp('relevance', $sort_by) == 0) {
		    usort($filtered_array, "cmp_ratio");
		}
		else if (strcmp('dates', $sort_by) == 0) {
		    usort($filtered_array, "cmp_date");
		}
                return $filtered_array;
            }
        }
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function get_redis_definitions($search_input, $sort_By, $functional_area) {
    $functional_areas_table = [
        "all" => "",
        "uwmadison" => "University of Wisconsin - Madison",
        "cirriculuminstruction" => "Curriculum & Instruction",
        "facultystaff" => "Faculty & Staff",
        "finance" => "Finance",
        "research" => "Research",
        "students" => "Students"
    ];
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_definitions')) {
            // Load all specifications into Redis?
        }

        $results = json_decode($redis->get('all_definitions'));

        // Default Landing
        // Still need to check for functional areas
        if (strcmp('', $search_input) == 0) {
            $filtered_array = array();
            foreach ($results as $result) {
                if (contains_area($result->functional_areas, $functional_area)) {
                    array_push($filtered_array, $result);
                }
            }
            return $filtered_array;
        }
        else {
            $filtered_array = array();

            //$input_array = explode(' ', $search_input);
            $input_array = preg_split("/[\s,\\/]+/", $search_input);
            foreach ($results as $result) {
                //$definition_array = explode(' ', $result->definition_name);
                $definition_array = preg_split("/[\s,\\/]+/", $result->definition_name);
                $definition_word_count = count($definition_array);
                $word_occurrences = 0;

                foreach ($input_array as $key_word) {
                    foreach ($definition_array as $compare_word) {
                        $distance = levenshtein(strtolower($key_word), strtolower($compare_word));
                        if ($distance <= 1 && $distance >= -1) {
                            $word_occurrences++;
                        }
                    }
                }
                if ($word_occurrences > 0 && contains_area($result->functional_areas, $functional_area)) {
                    $ratio = $word_occurrences / (float) $definition_word_count;
                    $result->ratio = $ratio;
                    array_push($filtered_array, $result);
                }
            }
            usort($filtered_array, "cmp_ratio");
            return $filtered_array;
        }
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function get_redis_specification_by_id($specification_id) {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_specifications')) {
            // Load all specifications into Redis?
        }

        $results = json_decode($redis->get('all_specifications'));

	foreach ($results as $result) {
	    if ($result->specification_id == $specification_id) {
		return $result;
	    }
	}
	return null;
    }
    catch (Exception $e) {
	return $e->getMessage();
    }
}

function get_redis_definitions_by_specification_id($specification_id) {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_specifications')) {
            // Load all specifications into Redis?
        }

        $relations = json_decode($redis->get('all_relations'));
	$definitions = json_decode($redis->get('all_definitions'));

	$definition_ids = array();
	foreach ($relations as $relation) {
	    if ($specification_id == $relation->specification_id) {
		array_push($definition_ids, $relation->definition_id);
	    }
	}

	$definition_results = array();
	foreach ($definitions as $definition) {
	    foreach ($definition_ids as $definition_id) {
		if ($definition->definition_id == $definition_id) {
		    array_push($definition_results, $definition);
		}
	    }
	}
	return $definition_results;
    }
    catch (Exception $e) {
	return $e->getMessage();
    }
}

function get_redis_specifications_by_definition($definition_id) {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_specifications')) {
            // Load all specifications into Redis?
        }

        $results = json_decode($redis->get('all_specifications'));
	$relations = json_decode($redis->get('all_relations')); 
	// for each relation, select the specification whose id matches the relation
	
	$filtered_results = array();
	foreach ($relations as $relation) {
	    //array_push($filtered_results, $relation);
	    $relation_def_id = $relation->definition_id;
	    $relation_spec_id = $relation->specification_id;
	    if ($relation_def_id == $definition_id) {
		foreach ($results as $result) {
		    if ($result->specification_id == $relation_spec_id) {
			array_push($filtered_results, $result);
		    }
		}
	    }
	}
	return $filtered_results;
    }
    catch (Exception $e) {
	return $e->getMessage();
    }
}

function get_redis_relations() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));

        $results = json_decode($redis->get('all_relations'));
        return $results;
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function get_redis_specification_functional_areas() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_specification_functional_areas')) {
        }

        $results = json_decode($redis->get('all_specification_functional_areas'));
        return $results;
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function get_redis_definition_functional_areas() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_definition_functional_areas')) {
        }

        $results = json_decode($redis->get('all_definition_functional_areas'));
        return $results;
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function get_redis_specification_types() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_specification_types')) {
        }

        $results = json_decode($redis->get('all_specification_types'));
        return $results;
    }
    catch (Exception $e) {
        return $e->getMessage();
    }
}

function get_redis_restrictions() { 
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        if (!$redis->exists('all_restrictions')) {
        }

        $results = json_decode($redis->get('all_restrictions'));
        return $results;
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

function cmp_date($a, $b) {
    $a_date = strtotime($a->last_revised);
    $b_date = strtotime($b->last_revised);
    return ($a_date > $b_date ? -1 : 1);
}

function contains_area($functional_areas, $area) {
    if (strcmp("", $area) == 0 || strcmp("all", $area) == 0 || strcmp("none", $area) == 0) {
        return true;
    }
    $pos = strpos($functional_areas, $area);
    if ($pos === false) {
        return false;
    }
    return true;
}

function specifications_set() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        return $redis->exists('all_specifications');
    }
    catch (Exception $e) {
        return false;
    }
}

function definitions_set() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        return $redis->exists('all_definitions');
    }
    catch (Exception $e) {
        return false;
    }
}

function relations_set() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        return $redis->exists('all_relations');
    }
    catch (Exception $e) {
        return false;
    }
}

function functional_areas_set() {
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        return $redis->exists('functional_areas');
    }
    catch (Exception $e) {
        return false;
    }
}

function restrictions_set() { 
    try {
        $redis = new Predis\Client(array(
            'host' => 'localhost',
            'port' => 26379
        ));
        return $redis->exists('all_restrictions');
    }
    catch (Exception $e) {
        return false;
    }
}
?>
