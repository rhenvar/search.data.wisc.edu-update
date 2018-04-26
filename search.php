<?php

include_once 'datalayer.php';
include_once 'redis_layer.php';

$search_input = '';
$sort_by = 'relevance';
$type = 'dashboardsReports';
$functional_area = 'all';
$page = 1;

if (isset($_GET['subsearch']) && isset($_GET['subsearch_type'])) {
    $subsearch_type = $_GET['subsearch_type'];
    if (0 == strcmp("reports_by_definition", $subsearch_type)) {
        $definition_id = $_GET['definition_id'];
        $result = get_reports_by_definition($definition_id);

        header("Content-type: application/json");
        print(json_encode($result));
    }
}

else {
    if (isset($_GET['search_input'])) {
        $search_input = $_GET['search_input'];
    }
    if (isset($_GET['sort_by'])) {
        $sort_by = $_GET['sort_by'];
    }
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    }
    if (isset($_GET['functional_area'])) {
        $functional_area = $_GET['functional_area'];
    }
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }

    if (strcmp($type, "dashboardsReports") == 0 && specifications_set()) {
        $result = get_redis_specifications($search_input, $sort_by, $functional_area);
        header("Length: " . count($result));
        header("Page: " . $page);
        $result = array_slice($result, 25 * ($page - 1), 25);
        header("Content-type: application/json");
        print(json_encode($result));
    }

    else if (strcmp($type, "dataDefinitions") == 0 && definitions_set()) {
        $result = get_redis_definitions($search_input, $sort_by, $functional_area);
        header("Length: " . count($result));
        header("Page: " . $page);
        $result = array_slice($result, 25 * ($page - 1), 25);
        header("Content-type: application/json");
        print(json_encode($result));
    }
    else {
        $result = array();//get_results($search_input, $type, $sort_by, $functional_area);
        header("Content-type: application/json");
        print(json_encode($result));
    }
}
?>
