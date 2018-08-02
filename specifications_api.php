<?php

include_once 'datalayer.php';
include_once 'redis_layer.php';

if (!isset($_GET['report_id'])) {
    
}
else {
    $specification = get_redis_specification_by_id($_GET['report_id']);
    $definitions = get_redis_definitions_by_specification_id($_GET['report_id']);
    //header("Content-type: application/json");

    //print(json_encode($specification));
    //print(json_encode($definitions));
}
?>

<html>
<head>
<?php
readfile('./header.html');
?>
	
</head>
<body>
<div class="uw-global-bar">
    <a class="uw-global-name-link" href="http://www.wisc.edu" id="main_link">U<span>niversity <span class="uw-of">of</span> </span>W<span>isconsin</span>–Madison</a>
</div>

<br/>
<br/>

<h2> <?= $specification->specification_name ?></h2>
<div class="api_tables">
    <table id="dashboards_reports_table">
	<tr>
	    <th>Name</th>
	</tr>
	<tr>
	    <td class="list_td"><?= $specification->specification_name ?></td>
	</tr>
    </table>
    <table id="dashboards_reports_table">
	<tr>
	    <th>Type</th>
	</tr>
	<tr>
	    <td class="list_td"><?= $specification->specification_type ?></td>
	</tr>
    </table>
    <table id="dashboards_reports_table">
	<tr>
	    <th>Description</th>
	</tr>
	<tr>
	    <td class="list_td"><?= $specification->description_val ?></td>
	</tr>
    </table>
    <table id="dashboards_reports_table">
	<tr>
	    <th>Important Notes</th>
	</tr>
	<tr>
	    <td class="list_td"><?= $specification->additional_details ?></td>
	</tr>
    </table>
    <table id="dashboards_reports_table">
	<tr>
	    <th>Data Domain</th>
	</tr>
	<tr>
	    <td class="list_td"><?= $specification->functional_areas ?></td>
	</tr>
    </table>
</div>


<br/>
<br/>

<h2>Related Definitions</h2>
<div class="api_tables">
    <?php if (count($definitions) > 0)  { ?>
	<table id="data_definitions_table">
	    <tr>
		<th>Name</th>
		<th>Functional Definition</th>
		<th>Data Domain</th>
	    </tr>
	    <?php foreach ($definitions as $definition) { ?>
	    <tr>
		<td><?= $definition->definition_name ?></td>
		<td><?= $definition->functional_definition ?></td>
		<td><?= $definition->functional_areas ?></td>
	    </tr>
	    <?php } ?>
	</table>
    <?php } else { ?>
	    <h3>No Related Definitions</h3>
    <?php } ?>
</div>

<div class="uw-global-footer">
  <p>&copy; 2018 Board of Regents of the University of Wisconsin System</p>
</div>
</body>
</html>
