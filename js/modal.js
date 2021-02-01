// MENU AND SETTINGS MODALS
var allModals = document.getElementsByClassName("modal");

var navigationModal = document.getElementById("navigationModal");
var navigationButton = document.getElementById("navigationButton");
var navigationClose = document.getElementById("navigationClose");

navigationButton.onclick = function() {
    navigationModal.style.display = "block";
}

navigationClose.onclick = function() {
    navigationModal.style.display = "none";
}

var settingsModal = document.getElementById("settingsModal");
var settingsButton = document.getElementById("settingsButton");
var settingsClose = document.getElementById("settingsClose");

settingsButton.onclick = function() {
    settingsModal.style.display = "block";
}

settingsClose.onclick = function() {
    settingsModal.style.display = "none";
}

// This works for the second modal, but not the first... Why?
window.onclick = function(event) {
    if ((event.target == navigationModal) || (event.target == settingsModal)) {
        for (i = 0; i < allModals.length; ++i) {
            allModals[i].style.display = "none";
        }
    }
}

// Replace this shit with an AJAX request.
let referenceList = document.getElementById("referenceitems").children;
let referenceItems = []

for (i = 0; i < referenceList.length; i++) {
    referenceItems.push(referenceList[i].innerText);
}

// Next thing.
for (i = 0; i < referenceList.length; i++) {
    $("p:contains('" + referenceItems[i] + "')").html(function (_, html) {
        regex = new RegExp(referenceItems[i], "gi");
        return html.replace(regex, '<a data-reference="' + referenceItems[i] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[i] + '</a>');
    });
}

// Get the modal
let modal = document.getElementById("myModal");

// Get the <span> element that closes the modal
let span = document.getElementsByClassName("close")[0];

function getModalContent(identifier) {
    getContent($(identifier).data('reference'));
}

function getContent(str) {
    if (str.length == 0) {
        document.getElementById("modal-data").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("modal-data").innerHTML = this.responseText;

                spoilerlevel = parseInt(localStorage.getItem("WallofHistorySpoilerLevel"));
                children = document.getElementById("modal-data").children;
                for (i = 0; i < children.length; i++) {
                    if (children[i].hasAttribute("data-spoiler")) {
                        if (parseInt(children[i].getAttribute("data-spoiler")) > spoilerlevel) {
                            children[i].style.display = "none";
                        }
                    }
                }
            }
        };
        xmlhttp.open("GET", "../php/getmodaldata.php?q=" + str, true);
        xmlhttp.send();
    }
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
    document.getElementById("modal-data").innerHTML = "<p>No information is available at this time.</p>";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.getElementById("modal-data").innerHTML = "<p>No information is available at this time.</p>";
    }
}