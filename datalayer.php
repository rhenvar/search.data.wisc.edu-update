<?php

include_once 'specification.php';
include_once 'data_definition.php';
include_once 'query_builder.php';
include_once 'relation.php';


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
                        $json_object->{'last_revised'},
                        0, // default ratio value
                        $json_object->{'attribute_7_name'},
                        $json_object->{'attribute_7_value'}
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
                        $json_object->{'last_revised'},
                        0, // default ratio value
                        $json_object->{'attribute_7_name'},
                        $json_object->{'attribute_7_value'}
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

function get_all_relations() {
    try {
        $qb = new QueryBuilder("", "dashboardsReports", "relevance", "");
        $link = new PDO('mysql:host=reporting.datacookbook.com', 'uwmadison', '7d1&c9*bsF7A');
        $link->exec('USE itdb_production');

        $stmt = $link->query($qb->get_all_relations(), PDO::FETCH_ASSOC);

        if ($stmt) {
            $stmt_array = array();

            foreach ($stmt as $database_result) {
                $json_object = json_decode(json_encode($database_result));
                $relation = new Relation($json_object->{'specification_id'}, $json_object->{'definition_id'});
                array_push($stmt_array, $relation);
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

function get_specification_functional_areas() {
    try {
        $qb = new QueryBuilder("", "dashboardsReports", "relevance", "");
        $link = new PDO('mysql:host=reporting.datacookbook.com', 'uwmadison', '7d1&c9*bsF7A');
        $link->exec('USE itdb_production');

        $stmt = $link->query($qb->get_specification_functional_areas(), PDO::FETCH_ASSOC);

        if ($stmt) {
            $stmt_array = array();

            foreach ($stmt as $database_result) {
                $json_object = json_decode(json_encode($database_result));
                array_push($stmt_array, $json_object->{'functional_areas'});
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

function get_definition_functional_areas() {
    try {
        $qb = new QueryBuilder("", "dataDefinitions", "relevance", "");
        $link = new PDO('mysql:host=reporting.datacookbook.com', 'uwmadison', '7d1&c9*bsF7A');
        $link->exec('USE itdb_production');

        $stmt = $link->query($qb->get_definition_functional_areas(), PDO::FETCH_ASSOC);

        if ($stmt) {
            $stmt_array = array();

            foreach ($stmt as $database_result) {
                $json_object = json_decode(json_encode($database_result));
                array_push($stmt_array, $json_object->{'functional_areas'});
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

function get_specification_types() {
    try {
        $qb = new QueryBuilder("", "dataDefinitions", "relevance", "");
        $link = new PDO('mysql:host=reporting.datacookbook.com', 'uwmadison', '7d1&c9*bsF7A');
        $link->exec('USE itdb_production');

        $stmt = $link->query($qb->get_specification_types(), PDO::FETCH_ASSOC);

        if ($stmt) {
            $stmt_array = array();

            foreach ($stmt as $database_result) {
                $json_object = json_decode(json_encode($database_result));
                array_push($stmt_array, $json_object->{'specification_type'});
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
