(function() {
    "use strict";

    window.onload = function() {
        setup();
    }

    function setup() {
       document.getElementById('submit').onclick = search; 
        $('#search_input').keyup(function(event) {
                if (event.keyCode === 13) {
                    search();
                }
        });
    }

    function search() {
        //document.getElementByName('result_container').innerHTML = "";
        var searchType = "all";
        var searchValue = document.getElementById("search_input").value;
        window.location.replace("search_results.php?search_input=" + searchValue + "&search_type=dashboardsReports");
    }
// Comment
})();
