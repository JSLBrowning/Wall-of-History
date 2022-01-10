document.addEventListener("DOMContentLoaded", function(){
    downloadContent();
    stackHistory();
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
            $(swappables[i]).slideUp();
        }
    }

    let thisButton = swappableDiv.nextElementSibling;
    let labelOld = thisButton.innerText;
    let labelNew = thisButton.getAttribute('data-alternate');
    thisButton.innerText = labelNew;
    thisButton.dataset.alternate = labelOld;
}

// These can be tested on “The Legend of Mata Nui,” which has three parents.
function carouselRight(button) {
    let parents = button.parentElement.getElementsByTagName("h3");
    for (let i = 0; i < parents.length; i++) {
        let currentStyles = window.getComputedStyle(parents[i]);
        if ((currentStyles.display != "none") && (i < parents.length - 1)) {
            // Only works for two elements at the moment. Fix that.
            parents[i].style.display = "none";
            parents[i + 1].style.display = "block";
            break;
        } else {
            parents[i].style.display = "none";
            parents[i + 1].style.display = "none";
            parents[0].style.display = "block";
            break;
        }
    }
}

function carouselLeft(button) {
    let parents = button.parentElement.getElementsByTagName("h3");
    for (let i = 0; i < parents.length; i++) {
        let currentStyles = window.getComputedStyle(parents[i]);
        if ((currentStyles.display != "none") && (i < parents.length - 1)) {
            // Only works for two elements at the moment. Fix that.
            parents[i].style.display = "none";
            parents[i - 1].style.display = "block";
            break;
        } else {
            parents[0].style.display = "none";
            parents[i].style.display = "block";
            break;
        }
    }
}

function check() {
    if (sessionStorage.getItem("activeReadingOrder") === null && Object.keys(localStorage).filter(name => name.includes('readingOrder')).length > 1) {
        generateSelectionModal();
    } else if (sessionStorage.getItem("activeReadingOrder") === null) {
        sessionStorage.setItem("activeReadingOrder", "0");
    }
}

let checkInterval = window.setInterval(check(), 500);

function stackHistory() {
    // Make sure cookie exists, maybe??
    // Get current ID.
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

function downloadContent() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const currentID = urlParams.get('id');

    $.ajax({
        url: "http://localhost:8080/doc/downloads/" + currentID + ".zip",
        type: 'HEAD',
        error: function () {
            console.log("No downloads are available for this content.");
        },
        success: function () {
            console.log("Downloads are available for this content.");
            document.getElementById("downloadLink").href = "/doc/downloads/" + currentID + ".zip";

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    filename = this.responseText.replace(/<\/?[^>]+(>|$)/g, "") + ".zip"
                    // Fix the above regex to strip to alphanumeric.
                    // Other than that... this still works! Nice.
                    document.getElementById("downloadLink").download = filename;
                    $(document.getElementById("downloadLink")).fadeIn("slow");
                }
            };
            xmlhttp.open("GET", "../php/gettitle.php?q=" + currentID, true);
            xmlhttp.send();
        }
    });
}