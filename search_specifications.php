<?php

$user_input = '';
$search_type = '';
if (isset($_GET['search_input'])) {
    $user_input = $_GET['search_input'];
}
if (isset($_GET['search_type'])) {
    $search_type = $_GET['search_type'];
}

?>


<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type = "text/javascript" src="search_specifications.js"></script>
    <link href="datapage.css" re;="stylesheet" type="text/css">
    <link href="global.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="https://brand.wisc.edu/content/uploads/2017/06/cropped-favicon_512-32x32.png" sizes="32x32">
    <link rel="icon" href="https://brand.wisc.edu/content/uploads/2017/06/cropped-favicon_512-192x192.png" sizes="192x192">

    <title>
        DMA Specifications Portal
    </title>
</head>
<body>
<div class="title_container">
    <h1>Looking for dashboards, reports, or data definitions?</h1>
</div>

<div class="search_container">
    <div id="bar_container">
        <input type="text" id="search_input" placeholder="What are you looking for? E.g. &quot;enrollments&quot;, &quot;student course history&quot;, &quot;where do alumni live&quot;" id="search_bar">

        <input type="submit" value="Search" id="submit">
    </div>
    <br/>
    <div class="styled-select">
        Sort By: 
        <select id="sort_by">
            <option value="relevance">Relevance</option>
            <option value="datesascending">Dates Ascending</option>
            <option value="datesdescending">Dates Descending</option>
        </select>
    </div>
    <div class="styled-select">
        Functional Area:
        <select id="functional_area">
            <option value="all">All</option>
            <option value="uwmadison">University of Wisconsin - Madison</option>
            <option value="cirriculuminstruction">Cirriculum &amp; Instruction</option>
            <option value="facultystaff">Faculty &amp; Staff</option>
            <option value="finance">Finance</option>
            <option value="research">Research</option>
            <option value="students">Students</option>
        </select>
    </div>
    <br/>
    <br/>
</div>

<div class="title_container"><h3>Results</h3></div>
<div class="result_container">

    <table id="dashboards_reports_table">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Description</th>
            <th>Functional Areas</th>
            <th>Related Dashboards/Reports</th>
        </tr>
    </table>

    <table id="data_definitions_table">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Functional Definition</th>
            <th>Functional Areas</th>
            <th>Related Definitions</th>
            <th>Related Dashboards/Reports</th>
        </tr>
    </table>

    <div id="no_results">
        <h3>Sorry, no Results Found</h3>
    </div>
</div>

</body>
</html>
