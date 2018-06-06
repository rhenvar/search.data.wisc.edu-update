(function() {
    "use strict";

    window.onload = function() {
        setup();
    }

    function setup() {
        populateFunctionalAreas();
        populateSpecificationTypes();

        var url_string = window.location.href;
        var url = new URL(url_string);
        var input = url.searchParams.get("search_input");
        if (null != input && input.length > 0) {
            document.getElementById("search_input").value = input;
            search();
        }

        document.getElementById('submit').onclick = function() { search() };
        document.getElementById('sort_by').onchange = function() { search() };
        document.getElementById('functional_area').onchange = function() { search() };
        document.getElementById('specification_type').onchange = function() { search() };
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

        document.getElementById('loading').style.display = "block";
        document.getElementsByClassName('result_container')[0].style.display = "none";
        document.getElementById('dashboards_reports_table').style.display = "none";
        document.getElementById('dashboards_reports_table').innerHTML = "<tr><th>Name</th><th>Type</th><th>Description</th><th class='functional_area'>Data Domains</th><th>URL</th></tr>";
        document.getElementById('data_definitions_table').innerHTML =  "<tr><th>Name</th><th>Functional Definition</th><th>Functional Areas</th><th>Related Dashboards/Reports</th></tr>";
        document.getElementById('pages_table').innerHTML = "";


        var searchInput = document.getElementById("search_input").value;

        var ajax = new XMLHttpRequest();

        var sortByElement = document.getElementById('sort_by');
        var sortBy = sortByElement.options[sortByElement.selectedIndex].value;

        var type = 'dashboardsReports';

        var functionalElement = document.getElementById('functional_area');
        var functionalArea = functionalElement.options[functionalElement.selectedIndex].value;

        var typeElement = document.getElementById('specification_type');
        var typeVal = typeElement.options[typeElement.selectedIndex].value;

        var url = "search.php?search_input=" + searchInput + "&sort_by=" + sortBy + "&type=" + type + "&functional_area=" + functionalArea + "&specification_type=" + typeVal + "&page=" + page;

        ajax.open("GET", url, true);
        ajax.onload = processResponse;
        ajax.send();
    }

    function processResponse() {
        console.log(this.responseText);

        if (200 == this.status) {
            showReports(this);
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
        else {
            document.getElementById("results_title").innerHTML = "Results (" + response.getResponseHeader("length") + ")" + ", displaying 25 per page";
            var currentPage = response.getResponseHeader("page");
            populatePageTable(response.getResponseHeader("length"), currentPage);
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
            functionalCell.innerHTML = report['functional_areas'].replace(",", ", ");
            functionalCell.classList.add("functional_area");

            var urlVal = report['attribute_4_value'];
            if (null == urlVal || "null" == urlVal) {
                urlCell.innerHTML = "No links found";
            }
            else {
                urlCell.innerHTML = "<a href='" + urlVal + "' target='_blank'>Workbook URL</a>";
            }

/*
            var dateVal = report['last_revised'];
            if (null == dateVal || "null" == dateVal) {
                dateCell.innerHTML = "No Last Revision Date";
            }
            else {
                dateCell.innerHTML = report['last_revised'];
            }
            */
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

    function showDefinitions(response) {
        document.getElementById('dashboards_reports_table').style.display = 'none';
        document.getElementById('data_definitions_table').style.display = 'block';

        var definitionsTable = document.getElementById('data_definitions_table');

        var definitionsJson = JSON.parse(response.responseText);

        if (0 == definitionsJson.length) {
            noResults();
            return;
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
            functionalAreasCell.innerHTML = definition['functional_areas'];

            var relatedDashboardsElement = document.createElement("p");
            relatedDashboardsElement.innerHTML = "<a href='#sub_reports_table'>Related Dashboards/Reports</a>";
            relatedDashboardsElement.onclick = showRelatedReportsByDefinition;
            relatedReports.appendChild(relatedDashboardsElement);

        }
    }

    // Gray out background and show screen overlay of related reports
    function showRelatedReportsByDefinition() {
        var definitionId = this.parentElement.parentElement.firstChild.innerHTML;
        // need an overlay container for subReportsTable
        var subReportsTable = document.createElement("table");
        var hasResults = true;

        subReportsTable.classList.add("sub_reports");
        subReportsTable.style.color = 'gray';
        subReportsTable.innerHTML = "<tr><th>Name</th><th>Description</th><th>Functional Areas</th><th>URL</th><tr>";

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
                    var descriptionCell = newRow.insertCell();
                    var functionalCell = newRow.insertCell();
                    var urlCell = newRow.insertCell();

                    idCell.innerHTML = report['specification_id'];
                    nameCell.innerHTML = report['specification_name'];
                    descriptionCell.innerHTML = report['description_val'];
                    functionalCell.innerHTML = report['functional_areas'];
                    //relatedReports.innerHTML = "<a href='#sub_reports_overlay'>Related Dashboards/Reports</a>";

                    var urlVal = report['attribute_value'];
                    if (null == urlVal || "null" == urlVal) {
                        urlCell.innerHTML = "No links found";
                    }
                    else {
                        urlCell.innerHTML = "<a href='" + urlVal + "' target='_blank'>Workbook URL</a>";
                    }
                }
                subResultsContainer.appendChild(subReportsTable);
            }
        };
        ajax.send();

        // Put subReports table into an on-screen overlay
        var subResultsContainer = document.createElement('div');
        var coverElement = document.createElement('div');
        var cancelElement = document.createElement('a');
        cancelElement.onclick = deleteOverlayResults;

        coverElement.setAttribute('id', 'cover');
        cancelElement.setAttribute('class', 'cancel');
        cancelElement.innerHTML = "&times;";
        subResultsContainer.setAttribute('id', 'sub_reports_overlay');
        subResultsContainer.appendChild(cancelElement);

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
        document.getElementById('results_title').innerHTML = "Sorry, no Specifications found for term '" + input + "' under Data Domain '" + functionalArea + "'";
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
        var url = "search.php?get_specification_functional_areas=true"; 
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

    function populateSpecificationTypes() {
        var ajax = new XMLHttpRequest();
        var url = "search.php?get_specification_types=true"; 
        ajax.open("GET", url, true);
        ajax.onload = processSpecificationTypes;
        ajax.send();
    }

    function processSpecificationTypes() {
        var specificationTypes = document.getElementById("specification_type");
        var areas = JSON.parse(this.responseText);
        for (var i = 0; i < areas.length; i++) {
            specificationTypes.innerHTML += "<option value='" + areas[i] + "'>" + areas[i] + "</option>";
        }
    }
})();
