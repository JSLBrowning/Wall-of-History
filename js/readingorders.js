async function generateSelectionModal() {
    console.log("Generating selection modal...");
    // Get the modal
    let modal = document.getElementById("myModal");

    document.getElementById("modal-data").innerHTML = "<h2>Select a Route</h2>";
    for (let key in localStorage) {
        if (key.includes("readingOrder")) {
            let id = key.split(":");

            if (id[1] == "0") {
                // Change this to just go back to page you were on?
                document.getElementById("modal-data").innerHTML += "<button class=\"disambigButton\" onclick=\"jumpToSelection(\'" + id[1] + "\')\" id=\"" + id[1] + "\">BIONICLE</button>";
            } else {
                let title = await getTitle(id[1]);
                document.getElementById("modal-data").innerHTML += "<button class=\"disambigButton\" onclick=\"jumpToSelection(\'" + id[1] + "\')\" id=\"" + id[1] + "\">" + title + "</button>";
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
        toggleModal("myModal");
}

function getTitle(id) {
    const query = "SELECT title FROM story_content WHERE id='" + id + "' LIMIT 1";

    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/query.php?q=" + query + "&c=title", true);
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

// Reading order disambiguation button should only appear:
// 1. When pressing main read button.
// 2. When trying to use navigation arrows **ON AN AMBIGUOUS PAGE**.
