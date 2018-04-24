(function() {
    "use strict";

    window.onload = function() {
        setup();
    }

    function setup() {
        document.getElementById("store_specifications").onclick = storeSpecifications;
        document.getElementById("store_definitions").onclick = storeDefinitions;
        document.getElementById("store_relations").onclick = storeRelations;
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
