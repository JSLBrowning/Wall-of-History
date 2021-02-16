// MENU AND SETTINGS MODALS
var allModals = document.getElementsByClassName("modal");
var navigationButton = document.getElementById("navigationButton");
var navigationModal = document.getElementById("navigationModal");
var navigationClose = document.getElementById("navigationClose");
var settingsButton = document.getElementById("settingsButton");
var settingsModal = document.getElementById("settingsModal");
var settingsClose = document.getElementById("settingsClose");

navigationButton.onclick = function() {
    settingsButton.style.zIndex = "-1";
    // Settings button is still at 1 a second too late.
    navigationModal.style.display = "block";
}

navigationClose.onclick = function() {
    navigationModal.style.display = "none";
}

if (settingsModal) {
    settingsButton.onclick = function() {
        settingsModal.style.display = "block";
    }
}

if (settingsModal) {
    settingsClose.onclick = function() {
        settingsModal.style.display = "none";
    }
}

// This works for the second modal, but not the first... Why?
// Well, now it doesn't work for either of them. Great.
// Wait, it works for... the first one, on the homepage.
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        for (i = 0; i < allModals.length; i++) {
            allModals[i].style.display = "none";
            settingsButton.style.zIndex = "1";
        }
    }
}

// Replace this shit with an AJAX request.
let referenceList = document.getElementById("referenceitems").children;
let referenceItems = []

for (i = 0; i < referenceList.length; i++) {
    referenceItems.push(referenceList[i].innerText);
}

// Next thing. Add a regex for spaces and punctuation to fix the Makuta/Maku error on eba6c8.
for (i = 0; i < referenceList.length; i++) {
    $("p:contains('" + referenceItems[i] + "')").html(function(_, html) {
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
        xmlhttp.onreadystatechange = function() {
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
span.onclick = function() {
    modal.style.display = "none";
    document.getElementById("modal-data").innerHTML = "<p>No information is available at this time.</p>";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.getElementById("modal-data").innerHTML = "<p>No information is available at this time.</p>";
    }
}