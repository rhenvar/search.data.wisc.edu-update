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
    <a class="uw-global-name-link" href="http://www.wisc.edu" id="main_link">U<span>niversity <span class="uw-of">of</span> </span>W<span>isconsin</span>–Madison</a>
</div>

<div class="title_container">
    <a href="https://www.wisc.edu"><img id="main_logo" src="https://search.data.wisc.edu/uw-crest-web.png" alt="https://brand.wisc.edu/content/uploads/2016/11/uw-crest-color-300x180.png"></a>
    <div>
        <h1>Office of Data Management &amp; Analytics Services</h1>
        <h2>Dashboards and Reports</h2>
    </div>
    <div class='minibar'>
    </div>
    <div class='description'>
	Welcome to [NAME], UW-Madison's portal for institutional administrative dashboards and reports. Clicking the name of a dashboard or report will take you to the dashboard or report. Those that require authentication have a <img class='lock' src="/lock.png" id="paragraph_lock">. UW-Madison employees my request access to restricted dashboards or reports by clicking the "Access Restrictions" link for the dashboard or report. For additional information on this portal, see the [NAME] <a href='https://kb.wisc.edu/msndata/'>KnowledgeBase article</a>.
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
        Data Domain:
        <select id="functional_area">
            <option value="all">All</option>
        </select>
    </div>
    <div class="styled-select">
        Type:
        <select id="specification_type">
            <option value="all">All</option>
        </select>
    </div>
    <div class="styled-select">
	Access Restrictions:
	<select id="access_restrictions">
	    <option value="none">All</option>
	</select>
    </div>
    <div class="styled-select">
        Sort By:
        <select id="sort_by">
            <option value="relevance">Relevance</option>
            <option value="dates">Newest First</option>
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
            <th>Data Domain</th>
        </tr>
    </table>

    <table id="data_definitions_table">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Functional Definition</th>
            <th>Data Domains</th>
            <th>Related Definitions</th>
            <th>Related Dashboards/Reports</th>
        </tr>
    </table>
    <table id="pages_table">
    </table>
</div>

<img id="loading" src="https://search.data.wisc.edu/loading.gif">

<div class="uw-global-footer">
  <p>&copy; 2018 Board of Regents of the University of Wisconsin System</p>
</div>
</body>
</html>
