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
<?php
readfile('./header.html');
?>
    <script type = "text/javascript" src="search_specifications.js"></script>

</head>
<body>
<div class="uw-global-bar">
    <a class="uw-global-name-link" href="http://www.wisc.edu">U<span>niversity <span class="uw-of">of</span> </span>W<span>isconsin</span>–Madison</a>
</div>

<div class="title_container">
    <a href="https://www.wisc.edu"><img id="main_logo" src="https://search.data.wisc.edu/uw-crest-web.png" alt="https://brand.wisc.edu/content/uploads/2016/11/uw-crest-color-300x180.png"></a>
    <div>
        <h1>Office of Data Management &amp; Analytics Services</h1>
        <h2>Dashboards and Reports</h2>
    </div>
</div>

<div class="search_container">
    <div id="bar_container">
        <input type="text" id="search_input" placeholder="Search" id="search_bar">

        <input type="submit" value="Search" id="submit">
    </div>
    <br/>
    <br/>
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
    <div class="styled-select">
        Sort By: 
        <select id="sort_by">
            <option value="relevance">Relevance</option>
            <option value="dates">Last Revised</option>
        </select>
    </div>
    <br/>
    <br/>
</div>


<div class="result_container">
    <h3 id="results_title">Results</h3>

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
</div>

<img id="loading" src="https://search.data.wisc.edu/loading.gif">

</body>
</html>
