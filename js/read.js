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


function updateSpoilerLevel(id) {
    const query = "SELECT spoiler_level FROM story_metadata WHERE id = '" + id + "' LIMIT 1";

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


document.addEventListener("DOMContentLoaded", function () {
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