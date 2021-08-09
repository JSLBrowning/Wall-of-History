async function generateSelectionModal() {
    // Get the modal
    let modal = document.getElementById("myModal");

    document.getElementById("modal-data").innerHTML = "";
    for (let key in localStorage) {
        if (key.includes("readingOrder")) {
            let ID = key.split(":");

            if (ID[1] == "0") {
                // Change this to just go back to page you were on?
                document.getElementById("modal-data").innerHTML += "<button class=\"contentsButton\" onclick=\"jumpToSelection(\'" + ID[1] + "\')\" id=\"" + ID[1] + "\">BIONICLE</button>";
            } else {
                let title = await getTitle(ID[1]);
                document.getElementById("modal-data").innerHTML += "<button class=\"contentsButton\" onclick=\"jumpToSelection(\'" + ID[1] + "\')\" id=\"" + ID[1] + "\">" + title + "</button>";
            }
        }
    }

    // Each button has the ID of the reading order it's associated with (jumpTo(ID.VERSION)).
    // Upon being clicked, it will...
    // 1. Load that reading order title into the ActiveReadingOrder session storage item.
    // 2. Jump to a saved place or first page of the relevant items.

    var field = 's';
    var url = window.location.href;
    if(url.indexOf('?' + field + '=') != -1)
        sessionStorage.setItem("activeReadingOrder", "0");
    else if(url.indexOf('&' + field + '=') == -1)
        modal.style.display = "block";
}

function getTitle(id) {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/gettitle.php?q=" + id, true);
        xmlhttp.send();
    });
}

function jumpToSelection(id) {
    sessionStorage.setItem("activeReadingOrder", id);
    jumpTo();
}

// Read as standalone option will appear on pages with ID, lang, and version.
// Saved reading order will include version (ID.version).
// The web for versions will be fixed — graphic novels will only have their own chapters as children, so no ambiguity there is possible.
// Optimal languages for fetches and redirects can be accomplished using main.getOptimalLanguage (update that function to only consider selected version, IF THERE IS ONE (if (id.contains(".")))).