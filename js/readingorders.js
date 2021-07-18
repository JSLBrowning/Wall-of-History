function generateSelectionModal() {
    // Get the modal
    let modal = document.getElementById("myModal");
    // Get the <span> element that closes the modal
    let span = document.getElementsByClassName("close")[0];

    for (let key in localStorage) {
        if (key.includes("readingOrder")) {
            let ID = key.split(".")[0];

            if (ID = 0) {
                document.getElementById("modal-data").innerHTML = "<button class=\"contentsButton\" onclick=\"jumpToSelection(\"" + id + "\")\" id=\"" + key.split(".")[0] + "\">BIONICLE</button>";
            } else {
                document.getElementById("modal-data").innerHTML = "<button class=\"contentsButton\" onclick=\"jumpToSelection(\"" + id + "\")\" id=\"" + key.split(".")[0] + "\">" + getTitle + "</button>";
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

    modal.style.display;
}

function jumpToSelection(id) {
    sessionStorage.setItem("activeReadingOrder", id);
    jumpTo();
    /*
    if (localStorage.getItem("savePlace") === null) {
        let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
        let index, value;
        for (index = 0; index < readingOrder.length; ++index) {
            value = readingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                result = value;
                newID = readingOrder[index].substring(0, readingOrder[index].indexOf(":"));
                newLang = await getOptimalLanguage(newID);
                window.location.href = ("/read/?id=" + newID + "&lang=" + newLang + "&v=1");
                break;
            }
        }
    } else {
        window.location.href = localStorage.getItem("savePlace");
    }
    */
}

// Read as standalone option will appear on pages with ID, lang, and version.
// Saved reading order will include version (ID.version).
// The web for versions will be fixed — graphic novels will only have their own chapters as children, so no ambiguity there is possible.
// Optimal languages for fetches and redirects can be accomplished using main.getOptimalLanguage (update that function to only consider selected version, IF THERE IS ONE (if (id.contains(".")))).