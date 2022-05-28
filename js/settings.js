function loadSettings() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementsByClassName("story")[0].innerHTML = this.responseText;
            fixSettings();
        }
    };
    xmlhttp.open("GET", "../php/populatesettings.php", true);
    xmlhttp.send();
}


function fixSettings() {
    let WallofHistoryReadingOrder = localStorage.getItem("readingOrder:0").split(",");
    let settingsList = document.getElementById("sortable");

    for (index = 0; index < WallofHistoryReadingOrder.length; ++index) {
        let object = document.getElementById(WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":"))).parentElement;
        let childObject = document.getElementById(WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")))
        settingsList.appendChild(object);

        if (WallofHistoryReadingOrder[index].substr(WallofHistoryReadingOrder[index].length - 1) == "1") {
            childObject.checked = true;
        } else {
            childObject.checked = false;
        }
    }
}


function exportRoute() {
    console.log("To be implemented.");
    // 1. Get all checked items as if storing in localStorage.
    // 1.a. Alert user that this will export currently checked items, not their current stored route.
    // 2. Prompt user for route name (store on second line of file).
    // 2. Save to file (2001-01-01.route, maybe).
    // 3. Download file to user's computer.
}


function importRoute() {
    console.log("To be implemented.");
    // 1. Ask user if they want to override main route or import alongside it.
    // 2. Prompt user for file to import.
    // 3. Read file and validate contents.
    // 3.a. Split by ",".
    // 3.b. Splite each resulting array by ":".
    // 3.c. If every import[i][0] is a valid ID, save original string to localStorage.
}


document.onload = loadSettings();


$.noConflict();
jQuery(document).ready(function ($) {
    function initSortable() {
        $("#sortable").sortable();
        $("#sortable").disableSelection();
    }
    initSortable();

    let arrFields = new Array();

    function output(e) {
        if (e["currentTarget"]["lastElementChild"]["checked"]) {
            e["target"]["parentElement"]["classList"].add("checked");
        } else {
            e["target"]["parentElement"]["classList"].remove("checked");
        }

        let key = e["currentTarget"]["lastElementChild"]["defaultValue"];
        if (e["currentTarget"]["lastElementChild"]["checked"]) {
            arrFields[key] = true;
        } else {
            arrFields[key] = false;
        }
    }

    function initActions() {
        $(".ui-state-default").on("change", function (e) {
            output(e);
        });
        $(".ui-state-default input").on("click", function (e) {
            output(e);
        });
    }
    initActions();

    $("#submit").on("click", function () {
        let yesCount = 0;
        let values = $("#sortable input:checkbox").map(function () {
            if (this.checked) {
                yesCount++;
                return this.value + ":1";
            } else {
                return this.value + ":0";
            }
        }).get();
        if (yesCount < 2) {
            alert("Error: At least two items must be checked.");
        } else {
            localStorage.setItem("readingOrder:0", values);
            alert("Your reading order has been updated!");
        }
    });
});


function checkAll(sel) {
    if (sel.options[sel.selectedIndex].value == "everything") {
        let boxes = document.querySelectorAll('input[type=checkbox]');
        for (i = 0; i < boxes.length; i++) {
            boxes[i].checked = true;
        }
        alert("All items have been selected.");
    } else {
        let boxes = document.querySelectorAll("[data-tags*='" + sel.options[sel.selectedIndex].value + "']");
        for (i = 0; i < boxes.length; i++) {
            boxes[i].checked = true;
        }
        alert("All " + sel.options[sel.selectedIndex].value + " items have been selected.");
    }
}


function uncheckAll(sel) {
    if (sel.options[sel.selectedIndex].value == "everything") {
        let boxes = document.querySelectorAll('input[type=checkbox]');
        for (i = 0; i < boxes.length; i++) {
            boxes[i].checked = false;
        }
        alert("All items have been unselected.");
    } else {
        let boxes = document.querySelectorAll("[data-tags*='" + sel.options[sel.selectedIndex].value + "']");
        for (i = 0; i < boxes.length; i++) {
            boxes[i].checked = false;
        }
        alert("All " + sel.options[sel.selectedIndex].value + " items have been unselected.");
    }
}