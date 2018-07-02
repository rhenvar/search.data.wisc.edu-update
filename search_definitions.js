(function() {
    "use strict";

    window.onload = function() {
        setup();
    }

    function setup() {
        populateFunctionalAreas();

        var url_string = window.location.href;
        var url = new URL(url_string);
        var input = url.searchParams.get("search_input");
        if (null != input && input.length > 0) {
            document.getElementById("search_input").value = input;
            search();
        }

        document.getElementById('submit').onclick = function() { search() };
        document.getElementById('functional_area').onchange = function() { search() };
        document.getElementsByTagName("h1")[0].onclick = function() { window.open("https://data.wisc.edu"); }

        $('#search_input').keyup(function(event) {
            if (event.keyCode === 13) {
                search();
            }
        });

        // populate results on load
        search();
    }

    /*
     * Collects all information and sorts by:
     * - Search bar
     * - Sorting order
     * - Type
     * - Functional Area
     */
    function search(page) {
        page = (typeof page !== 'undefined') ? page : 1;

        document.getElementById("loading").style.display = "block";
        document.getElementsByClassName("result_container")[0].style.display = "none";
        document.getElementById('dashboards_reports_table').innerHTML = "<tr><th>Name</th><th>Type</th><th>Description</th><th class='functional_area'>Data Domain</th><th>URL</th></tr>";
        document.getElementById('data_definitions_table').innerHTML =  "<tr><th>Name</th><th>Functional Definition</th><th>Data Domain</th><th>Related Dashboards/Reports</th></tr>";
        document.getElementById('pages_table').innerHTML = "";


        var searchInput = document.getElementById("search_input").value;

        var ajax = new XMLHttpRequest();

        var sortBy = "relevance";

        var typeElements = document.getElementsByName('type');
        var type = 'dataDefinitions';

        var functionalElement = document.getElementById('functional_area');
        var functionalArea = functionalElement.options[functionalElement.selectedIndex].value;

        var url = "search.php?search_input=" + searchInput + "&sort_by=" + sortBy + "&type=" + type + "&functional_area=" + functionalArea + "&page=" + page;

        ajax.open("GET", url, true);
        ajax.onload = processResponse;
        ajax.send();
    }

    function processResponse() {
        console.log(this.responseText);

        if (200 == this.status) {
            showDefinitions(this);
            document.getElementById("loading").style.display = "none";
            document.getElementsByClassName('result_container')[0].style.display = 'block';
        }
    }

    function showReports(response) {
        document.getElementById('data_definitions_table').style.display = 'none';
        document.getElementById('dashboards_reports_table').style.display = 'block';

        var reportsTable = document.getElementById('dashboards_reports_table');

        var reportsJson = JSON.parse(response.responseText);

        if (0 == reportsJson.length) {
            noResults();
            return;
        }

        for (var i = 0; i < reportsJson.length; i++) {
            var report = reportsJson[i];
            var newRow = reportsTable.insertRow();

            var idCell = newRow.insertCell();
            idCell.style.display = 'none';
            var nameCell = newRow.insertCell();
            var typeCell = newRow.insertCell();
            var descriptionCell = newRow.insertCell();
            var functionalCell = newRow.insertCell();
            var urlCell = newRow.insertCell();

            idCell.innerHTML = report['specification_id'];
            nameCell.innerHTML = report['specification_name'];
            typeCell.innerHTML = report['specification_type'];
            descriptionCell.innerHTML = report['description_val'];
            functionalCell.innerHTML = report['functional_areas'];

            var urlVal = report['attribute_value'];
            if (null == urlVal || "null" == urlVal) {
                urlCell.innerHTML = "No links found";
            }
            else {
                urlCell.innerHTML = "<a href='" + urlVal + "' target='_blank'>Workbook URL</a>";
            }
        }
    }

    function showDefinitions(response) {
        document.getElementById('dashboards_reports_table').style.display = 'none';
        document.getElementById('data_definitions_table').style.display = 'block';

        var definitionsTable = document.getElementById('data_definitions_table');

        var definitionsJson = JSON.parse(response.responseText);

        if (0 == definitionsJson.length) {
            noResults();
            return;
        }
        else {
            document.getElementById("results_title").innerHTML = "Results (" + response.getResponseHeader("length") + ")" + ", displaying 25 per page";
            var currentPage = response.getResponseHeader("page");
            populatePageTable(response.getResponseHeader("length"), currentPage);
        }

        for (var i = 0; i < definitionsJson.length; i++) {
            var definition = definitionsJson[i];
            var newRow = definitionsTable.insertRow();

            var idCell = newRow.insertCell();
            idCell.style.display = 'none';
            var nameCell = newRow.insertCell();
            var definitionCell = newRow.insertCell();
            var functionalAreasCell = newRow.insertCell();
            var relatedReports = newRow.insertCell();

            idCell.innerHTML = definition['definition_id'];
            nameCell.innerHTML = definition['definition_name'];
            definitionCell.innerHTML = definition['functional_definition'];
            functionalAreasCell.innerHTML = definition['functional_areas'].replace(",", ", ");
            functionalAreasCell.classList.add("functional_area")

            var relatedDashboardsElement = document.createElement("p");
            if (definition['has_reports']) {
                relatedDashboardsElement.innerHTML = "<a href='#sub_reports_table_" + definition['definition_id'] +"'>Related Dashboards/Reports</a>";
                relatedDashboardsElement.onclick = showRelatedReportsByDefinition;
            }
            else {
                relatedDashboardsElement.innerHTML = "No related Dashboards/Reports";
            }
            relatedReports.appendChild(relatedDashboardsElement);
        }
    }

    function populatePageTable(count, currPage) {
        var pages = count <= 25 ? 1 : count / 25 + 1;
        var pageTable = document.getElementById("pages_table");
        var pageRow = pageTable.insertRow();

        for (var i = 1; i <= pages; i++) {
            var numberCell = pageRow.insertCell();
            numberCell.innerHTML = i;
            if (currPage == i) {
                numberCell.id = "selected_page";
            }
            else {
                numberCell.classList.add("unselected_page");
                numberCell.onclick = changePage;
            }
        }
    }

    function changePage() {
        var page = this.innerHTML;
        search(page);
    }

    // Gray out background and show screen overlay of related reports
    function showRelatedReportsByDefinition() {
        //this.childNodes[0].classList.add("visited");
        var definitionId = this.parentElement.parentElement.firstChild.innerHTML;
        // need an overlay container for subReportsTable
        var subReportsTable = document.createElement("table");
        var hasResults = true;

        subReportsTable.classList.add("sub_reports");
        subReportsTable.style.color = 'gray';
        subReportsTable.innerHTML = "<tr><th>Name</th><th>Type</th><th>Description</th><th class='functional_area'>Data Domain</th><th>Request Access</th></tr>";

        var ajax = new XMLHttpRequest();
        var url = "search.php?subsearch=true&subsearch_type=reports_by_definition&definition_id=" + definitionId;
        ajax.open("GET", url, true);

        ajax.onload = function() {
            console.log(this.responseText);
            var reportsJson = JSON.parse(this.responseText);

            if (0 == reportsJson.length) {
                // no related reports
                hasResults = false;
                var noReportsElement = document.createElement("h2");
                noReportsElement.innerHTML = "No Related Dashboards/Reports";
                subResultsContainer.appendChild(noReportsElement);

            }
            else {
                // Populate subReportsTable
                for (var i = 0; i < reportsJson.length; i++) {
		    var report = reportsJson[i];
		    var newRow = subReportsTable.insertRow();

		    var idCell = newRow.insertCell();
		    idCell.style.display = 'none';
		    var nameCell = newRow.insertCell();
		    var typeCell = newRow.insertCell();
		    var descriptionCell = newRow.insertCell();
		    var functionalCell = newRow.insertCell();
		    var urlCell = newRow.insertCell();

		    idCell.innerHTML = report['specification_id'];
		    var isPublic = report['attribute_7_value'];

		    var urlVal = report['attribute_4_value'];
		    nameCell.innerHTML += "<a href='" + urlVal + "' target='_blank'>" + report['specification_name'] + $
		    //if (null == urlVal || "null" == urlVal) {
		    if (isPublic !== 'publicly_available' ) {
				// insert lock icon
			    var img = new Image();
			    img.src= "/lock.png";
			    img.className = "lock";
			    img.style.float = "right";
			    nameCell.appendChild(img);
			    //nameCell.innerHTML = "<a href='" + urlVal + "' target='_blank'>" + report['specification_name$
			    urlCell.innerHTML = "<a href=" + report['attribute_8_value'] + "> Access Restrictions </a>";
		    }

		    if (report['attribute_7_value'] == 'publicly_available') {
			urlCell.innerHTML = "Publicly Available";
		    }
		    else if (report['attribute_7_value'] == 'all_employees') {
			urlCell.innerHTML = "UW-Madison Employees (NetID Required)";
		    }
		    else if (report['attribute_7_value'] == "specific_audience") {
			urlCell.innerHTML = "Specific/Limited Audience <br/> <a href=mailto:" + report['attribute_8_value'] + "> Request Access </a>";
		    }
		    else {
			urlCell.innerHTML = humanize(report['attribute_7_value']) + "<br/> <a href=" + report['attribute_8_value'] + "> Request Access </a>";
		    }


		    typeCell.innerHTML = report['specification_type'];
		    descriptionCell.innerHTML = report['description_val'];
		    functionalCell.innerHTML = report['functional_areas'];
		    functionalCell.innerHTML = report['functional_areas'].replace(",", ", ");
		    functionalCell.classList.add("functional_area");

                }
                subResultsContainer.appendChild(subReportsTable);
                //subResultsContainer.style.display = "block";
                $('#sub_reports_overlay').fadeIn();
            }
        };
        ajax.send();

        // Put subReports table into an on-screen overlay
        var subResultsContainer = document.createElement('div');
        subResultsContainer.style.display = "none";
        var coverElement = document.createElement('div');
        var cancelElement = document.createElement('a');
        cancelElement.onclick = deleteOverlayResults;

        coverElement.setAttribute('id', 'cover');
        cancelElement.setAttribute('class', 'cancel');
        cancelElement.innerHTML = "&times;";
        subResultsContainer.setAttribute('id', 'sub_reports_overlay');
        subResultsContainer.appendChild(cancelElement);

        subResultsContainer.style.display = "none";
        document.body.appendChild(coverElement);
        document.body.appendChild(subResultsContainer);
    }

    function processRelatedReportsByDefinition() {
    }

    function showRelatedDefinitionsByDefinition(node) {
    }

    function noResults() {
        document.getElementById("loading").style.display = "none";
        var input = document.getElementById("search_input").value;
        var functionalElement = document.getElementById('functional_area');
        var functionalArea = functionalElement.options[functionalElement.selectedIndex].innerHTML;

        document.getElementById('dashboards_reports_table').style.display = 'none';
        document.getElementById('data_definitions_table').style.display = 'none';
        document.getElementById("results_title").innerHTML = "Sorry, no Definitions found for term '" + input + "' under Data Domain '" + functionalArea + "'";
        document.getElementsByClassName("result_container")[0].style.display = "block";

    }

    function deleteOverlayResults() {
        var subResultsContainer = document.getElementById("sub_reports_overlay");
        var cover = document.getElementById("cover");
        subResultsContainer.parentNode.removeChild(subResultsContainer);
        cover.parentNode.removeChild(cover);
    }

    function populateFunctionalAreas() {
        var ajax = new XMLHttpRequest();
        var url = "search.php?get_definition_functional_areas=true"; 
        ajax.open("GET", url, true);
        ajax.onload = processFunctionalAreas;
        ajax.send();
    }

    function processFunctionalAreas() {
        var functionalAreas = document.getElementById("functional_area");
        var areas = JSON.parse(this.responseText);
        for (var i = 0; i < areas.length; i++) {
            //TODO: figure out values vs. names for option elements
            functionalAreas.innerHTML += "<option value='" + areas[i] + "'>" + areas[i] + "</option>";
        }
    }
    function humanize(str) {
	var frags = str.split('_');
	for (var i=0; i < frags.length; i++) {
	    frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
	}
	return frags.join(' ');
    }

})();
