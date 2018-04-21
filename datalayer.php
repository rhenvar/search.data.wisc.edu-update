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
                        $json_object->{'attribute_4_name'},
                        $json_object->{'attribute_4_value'},
                        $json_object->{'last_revised'}
                    );
                }
                else if (0 == strcmp('dataDefinitions', $type)) {

                    $has_reports = false;
                    $reports = get_reports_by_definition($json_object->{'definition_id'});
                    if (count($reports) > 0) {
                        $has_reports = true;
                    }

                    $obj = new DataDefinition($json_object->{'definition_id'},
                        $json_object->{'name'},
                        "Data Definition",
                        $json_object->{'functional_definition'},
                        $json_object->{'functional_areas'},
                        $has_reports
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
                        $json_object->{'attribute_4_name'},
                        $json_object->{'attribute_4_value'},
                        $json_object->{'last_revised'}
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
