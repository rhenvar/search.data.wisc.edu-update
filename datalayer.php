<?php

include_once 'specification.php';
include_once 'data_definition.php';
include_once 'query_builder.php';


function get_results($search_input, $type, $sort_by, $functional_area) {
    return get_results_by_relevance($search_input, $type, $sort_by, $functional_area);
}

function get_results_by_relevance($search_input, $type, $sort_by, $functional_area) {
    try {
        $qb = new QueryBuilder($search_input, $type, $sort_by, $functional_area); 
        $link = new PDO('mysql:host=reporting.datacookbook.com', 'uwmadison', '7d1&c9*bsF7A');
        $link->exec('USE itdb_production');

        $stmt = $link->query($qb->build_query(), PDO::FETCH_ASSOC);
        //$stmt = $link->query("SELECT *, ((specification_name LIKE '%Trends%')) AS count_words FROM specification_versions
        //                    WHERE (specification_name LIKE '%Trends%') ORDER BY count_words", PDO::FETCH_ASSOC);
        //$stmt = $link->query("SELECT * FROM specification_versions WHERE specification_name LIKE '%Trends%'", PDO::FETCH_ASSOC);

        if ($stmt) {
            $stmt_array = array();
            foreach ($stmt as $database_result) {
                $json_object = json_decode(json_encode($database_result));

                $obj = NULL;

                if (0 == strcmp('dashboardsReports', $type)) {
                    $obj = new Specification($json_object->{'specification_id'},
                        $json_object->{'specification_name'},
                        $json_object->{'specification_type'},
                        $json_object->{'description'},
                        $json_object->{'functional_areas'},
                        $json_object->{'attribute_1_name'},
                        $json_object->{'attribute_1_value'},
                        $json_object->{'last_revised'}
                    );
                }
                else if (0 == strcmp('dataDefinitions', $type)) {
                    $obj = new DataDefinition($json_object->{'definition_id'},
                        $json_object->{'name'},
                        "Data Definition",
                        $json_object->{'functional_definition'},
                        $json_object->{'functional_areas'});
                }

                array_push($stmt_array, $obj);
            }
            return $stmt_array;
        }
        else {
            return $link->errorInfo();
        }
    }
    catch (PDOException $e) {
        return $e->getMessage(); 
    }
}

function get_reports_by_definition($definition_id) {
    $type = 'dashboardsReports';
    try {
        $qb = new QueryBuilder("", "dashboardsReports", "relevance", ""); 
        $link = new PDO('mysql:host=reporting.datacookbook.com', 'uwmadison', '7d1&c9*bsF7A');
        $link->exec('USE itdb_production');

        //$stmt = $link->query($qb->get_reports_by_definition($definition_id), PDO::FETCH_ASSOC);
        $stmt = $link->query($qb->get_reports_by_definition($definition_id), PDO::FETCH_ASSOC);

        if ($stmt) {
            $stmt_array = array();

            foreach ($stmt as $database_result) {
                $json_object = json_decode(json_encode($database_result));

                $obj = NULL;

                if (0 == strcmp('dashboardsReports', $type)) {
                    $obj = new Specification($json_object->{'specification_id'},
                        $json_object->{'specification_name'},
                        $json_object->{'specification_type'},
                        $json_object->{'description'},
                        $json_object->{'functional_areas'},
                        $json_object->{'attribute_1_name'},
                        $json_object->{'attribute_1_value'}
                    );
                }

                array_push($stmt_array, $obj);
            }
            return $stmt_array;
        }
        else {
            return $link->errorInfo();
        }
    }
    catch (PDOException $e) {
        return $e->getMessage(); 
    }
}
?>
