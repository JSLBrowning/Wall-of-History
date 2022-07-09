document.addEventListener("DOMContentLoaded", function () {
    stackHistory();

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const currentID = urlParams.get('id');
    updateSpoilerLevel(currentID);

    // Ensure equivalent stories dropdown menu is selected.
    try {
        document.getElementById("equivalentSelect").selectedIndex = "0";
    } catch (e) {
        console.log("No equivalent stories.");
    }

    showButtons();
});


function swap(swappableID) {
    let swappableDiv = document.getElementById(swappableID);
    let swappables = swappableDiv.children;
    for (let i = 0; i < swappables.length; i++) {
        let currentStyles = window.getComputedStyle(swappables[i]);
        if (currentStyles.display === "none" || currentStyles.display === "") {
            // swappables[i].style.display = "block";
            setTimeout(() => {
                $(swappables[i]).slideDown();
            }, 400);
        } else {
            // swappables[i].style.display = "none";
            $("video").each(function () {
                $(this).get(0).pause();
            });
            $(swappables[i]).slideUp();
        }
    }

    let thisButton = swappableDiv.nextElementSibling;
    let labelOld = thisButton.innerText;
    let labelNew = thisButton.getAttribute('data-alternate');
    thisButton.innerText = labelNew;
    thisButton.dataset.alternate = labelOld;
}


function check() {
    if (sessionStorage.getItem("activeReadingOrder") === null && Object.keys(localStorage).filter(name => name.includes('readingOrder')).length == 1) {
        sessionStorage.setItem("activeReadingOrder", "0");
    }
}


let checkInterval = window.setInterval(check(), 500);


function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


function stackHistory() {
    // Make sure cookie exists, maybe??
    // Get current ID.
    // Update so it won't log nulls.
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ID = urlParams.get("id");
    const version = (urlParams.has("v")) ? urlParams.get("v") : "1";

    // Run through stackhistory.php with XmlHttpRequest.
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Split result into array.
            let newStackItems = this.responseText.split(",");

            // Get existing historyStack from cookies (if it exists), split into array.
            let existingStackItems = (getCookie("historyStack") === "") ? [] : getCookie("historyStack").split(",");

            // Stack new historyStack with existing historyStack.
            for (i = 0; i < newStackItems.length; i++) {
                existingStackItems.push(newStackItems[i]);
            }

            // If > 20, remove oldest items.
            while (existingStackItems.length > 20) {
                existingStackItems.shift();
            }

            // Join array into string.
            let newStack = existingStackItems.join(",");

            // Set cookie.
            document.cookie = "historyStack=" + newStack + "; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
            console.log("History stack updated.");
        }
    };
    xmlhttp.open("GET", "../php/stackhistory.php?id=" + ID + "&v=" + version, true);
    xmlhttp.send();
}


function updateSpoilerLevel(id) {
    const query = "SELECT spoiler_level FROM woh_metadata WHERE id = '" + id + "' LIMIT 1";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let spoilerLevel = this.responseText;
            if ($.isNumeric(spoilerLevel)) {
                localStorage.setItem("spoilerLevel", parseInt(spoilerLevel));
            } else {
                console.log("Non-integer spoiler level returned. Maintaining previous value. Please report to admin@wallofhistory.com.")
            }
        }
    };
    xmlhttp.open("GET", "../php/query.php?q=" + query + "&c=" + "spoiler_level", true);
    xmlhttp.send();
}


function zoomExtras(type) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const ID = urlParams.get("id");
    const version = (urlParams.has("v")) ? urlParams.get("v") : "1";
    const language = (urlParams.has("lang")) ? urlParams.get("lang") : "en";

    const query = "SELECT extra_main FROM story_reference_extras WHERE id='" + ID + "' AND content_version=" + version + " AND content_language='" + language + "' AND extra_type='" + type + "' LIMIT 1";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let extra = this.responseText;
            let div = document.createElement("div");
            div.innerHTML = extra.trim();

            let zoom = document.createElement("div");
            zoom.classList.add("zoom");
            document.body.appendChild(zoom);

            zoom.appendChild(div.firstChild)

            let exit = document.createElement("span");
            exit.classList.add("exitSpan");
            exit.setAttribute("onclick", "closeImage();");
            exit.innerHTML = "";
            zoom.appendChild(exit);
            $(".zoom").fadeTo("fast", 1);
        }
    };
    xmlhttp.open("GET", "../php/query.php?q=" + query + "&c=extra_main", true);
    xmlhttp.send();
}
