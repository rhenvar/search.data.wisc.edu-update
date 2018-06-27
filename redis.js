(function() {
    "use strict";

    window.onload = function() {
        setup();
    }

    function setup() {
        document.getElementById("store_specifications").onclick = storeSpecifications;
        document.getElementById("store_definitions").onclick = storeDefinitions;
        document.getElementById("store_relations").onclick = storeRelations;
        document.getElementById("store_specification_functional_areas").onclick = storeSpecificationFunctionalAreas;
        document.getElementById("store_definition_functional_areas").onclick = storeDefinitionFunctionalAreas;
        document.getElementById("store_specification_types").onclick = storeSpecificationTypes;
	document.getElementById("store_restrictions").onclick = storeRestrictions;
    }

    function storeSpecifications() {
        var ajax = new XMLHttpRequest();

        var url = "redis_manager.php?store_all_specifications=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function storeDefinitions() {
        var ajax = new XMLHttpRequest();

        var url = "redis_manager.php?store_all_definitions=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function storeRelations() {
        var ajax = new XMLHttpRequest();

        var url = "redis_manager.php?store_all_relations=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function storeSpecificationFunctionalAreas() {
        var ajax = new XMLHttpRequest();
        var url = "redis_manager.php?store_all_specification_functional_areas=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function storeDefinitionFunctionalAreas() {
        var ajax = new XMLHttpRequest();
        var url = "redis_manager.php?store_all_definition_functional_areas=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function storeSpecificationTypes() {
        var ajax = new XMLHttpRequest();
        var url = "redis_manager.php?store_all_specification_types=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function storeRestrictions() { 
        var ajax = new XMLHttpRequest();
        var url = "redis_manager.php?store_all_restrictions=true";

        ajax.open("GET", url, true);
        ajax.onload = processStoreSpecificationsResponse;
        ajax.send();
    }

    function processStoreSpecificationsResponse() {
        console.log(this.responseText);

        if (200 == this.status) {
            alert(this.responseText);
        }
        else {
            alert("Failed to store data in Redis");
        }
    }

})();
