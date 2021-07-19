async function generateSelectionModal() {
    // Get the modal
    let modal = document.getElementById("myModal");
    // Get the <span> element that closes the modal
    let span = document.getElementsByClassName("close")[0];

    for (let key in localStorage) {
        if (key.includes("readingOrder")) {
            let ID = key.split(":")[1];

            if (ID = 0) {
                document.getElementById("modal-data").innerHTML = "<button class=\"contentsButton\" onclick=\"jumpToSelection(\'" + ID + "\')\" id=\"" + ID + "\">BIONICLE</button>";
            } else {
                let title = await getTitle(ID);
                document.getElementById("modal-data").innerHTML = "<button class=\"contentsButton\" onclick=\"jumpToSelection(\'" + ID + "\')\" id=\"" + ID + "\">" + title + "</button>";
            }
        }
    }

    // Each button has the ID of the reading order it's associated with (jumpTo(ID.VERSION)).
    // Upon being clicked, it will...
    // 1. Load that reading order title into the ActiveReadingOrder session storage item.
    // 2. Jump to a saved place or first page of the relevant items.
    /*
    <button class='contentsButton' onclick='window.location.href="/read/?id=0343U0";'>
        <div class='contentsImg'><img src='https://wallofhistory.com/img/test/1.png'></div>
        <div class='contentsText'>
            <p>Chapter 1: Quest for the Masks</p>
            <p>Six Toa heroes must discover how to work together to save the island paradise of Mata Nui from destruction by the shadows of the Makuta. Only hidden golden Kanohi masks of power will let the Toa t…</p>
        </div>
    </button>
    */

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
    console.log("1");
    sessionStorage.setItem("activeReadingOrder", id);
    jumpTo();
}

// Read as standalone option will appear on pages with ID, lang, and version.
// Saved reading order will include version (ID.version).
// The web for versions will be fixed — graphic novels will only have their own chapters as children, so no ambiguity there is possible.
// Optimal languages for fetches and redirects can be accomplished using main.getOptimalLanguage (update that function to only consider selected version, IF THERE IS ONE (if (id.contains(".")))).