function initialize() {
    if (localStorage.getItem("version") != "1.0") {
        // Try to convert saved place first.
        localStorage.setItem("version", "1.0");
    }

    if ((localStorage.getItem("languagePreference") === null) || (localStorage.getItem("languageList") === null)) {
        // Step 1: Get preferred language.
        const lang = navigator.language;
        localStorage.setItem("languagePreference", lang.substring(0, 2));

        // Step 2: Get all languages and put them in a list.
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("languageList", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/getlanguagelist.php", true);
        xmlhttp.send();
    }

    // Step 3: If preferred language is in language list, bring it to the front.
    if (localStorage.getItem("languageList").includes(localStorage.getItem("languagePreference"))) {
        let languageList = localStorage.getItem("languageList").split(",");
        let preferred = localStorage.getItem("languagePreference");
        languageList.sort(function(x, y) { return x == preferred ? -1 : y == preferred ? 1 : 0; });
        localStorage.setItem("languageList", languageList);
    }

    if (localStorage.getItem("colorScheme") === null) {
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches) {
            localStorage.setItem("colorScheme", "light");
        } else {
            localStorage.setItem("colorScheme", "dark");
        }
    }

    if (localStorage.getItem("readingOrder:0") === null || localStorage.getItem("version") === null) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("readingOrder:0", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initread.php", true);
        xmlhttp.send();
    }

    if (localStorage.getItem("spoilerLevel") === null) {
        localStorage.setItem("spoilerLevel", "1");
    }

    if (localStorage.getItem("referenceTerms") === null) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("referenceTerms", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/getreferenceterms.php", true);
        xmlhttp.send();
    }
};

initialize();

function resetReader() {
    localStorage.clear();
    localStorage.setItem("version", "1.0");

    // Step 1: Get preferred language.
    const lang = navigator.language;
    localStorage.setItem("languagePreference", lang.substring(0, 2));

    // Step 2: Get all languages and put them in a list.
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // localStorage.setItem("languageList", this.responseText);
            localStorage.setItem("languageList", "es,en");

            // Step 3: If preferred language is in language list, bring it to the front.
            let languageList = localStorage.getItem("languageList").split(",");
            let preferred = localStorage.getItem("languagePreference");
            languageList.sort(function(x, y) { return x == preferred ? -1 : y == preferred ? 1 : 0; });
            localStorage.setItem("languageList", languageList);
        }
    };
    xmlhttp.open("GET", "../php/getlanguagelist.php", true);
    xmlhttp.send();

    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches) {
        localStorage.setItem("colorScheme", "light");
    } else {
        localStorage.setItem("colorScheme", "dark");
    }

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("readingOrder:0", this.responseText);
            alert("Reset complete.");
        }
    };
    xmlhttp.open("GET", "../php/initread.php", true);
    xmlhttp.send();

    localStorage.setItem("spoilerLevel", "1");

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("referenceTerms", this.responseText);
        }
    };
    xmlhttp.open("GET", "../php/getreferenceterms.php", true);
    xmlhttp.send();
}

function activateReadingOrder() {
    if (sessionStorage.getItem("activeReadingOrder") === null) {
        const keyArray = Object.keys(localStorage);
        let readingOrders = keyArray.filter(name => name.includes('readingOrder'));
        if (readingOrders.length == 1) {
            sessionStorage.setItem("activeReadingOrder", readingOrders[0]);
        } else {
            alert("Hmm…");
        }
    }
}

activateReadingOrder();

/* READER NAVIGATION HELPERS */

/*
function getOptimalLanguage(id) {
    let languageList = localStorage.getItem("languageList").split(",");
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            availableLanguages = this.responseText.split(",");

            let a = [];
            for (i = 0; i < availableLanguages.length; i++) {
                a.push(availableLanguages[i]);
            }

            let intersection = languageList.filter(x => a.includes(x));
            return intersection[0];
        }
    };
    xmlhttp.open("GET", "../php/getavailablelanguages.php?q=" + id, true);
    xmlhttp.send();
}
*/

function getOptimalLanguage(id) {
    return new Promise(resolve => {
        let languageList = localStorage.getItem("languageList").split(",");
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                availableLanguages = this.responseText.split(",");

                let a = [];
                for (i = 0; i < availableLanguages.length; i++) {
                    a.push(availableLanguages[i]);
                }

                let intersection = languageList.filter(x => a.includes(x));
                resolve(intersection[0]);
            }
        };
        xmlhttp.open("GET", "../php/getavailablelanguages.php?q=" + id, true);
        xmlhttp.send();
    });
}

/* READER NAVIGATION */

function findSelf() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);
    for (index = 0; index < readingOrder.length; index++) {
        if ((readingOrder[index].substring(0, readingOrder[index].indexOf(":"))) == urlParams.get('id')) {
            result = index;
        }
    }
    return (result);
}

function filteredSelf() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    const urlParams = new URLSearchParams(window.location.search);

    let index;
    let goodValues = [];
    for (index = 0; index < readingOrder.length; index++) {
        value = readingOrder[index];
        if (value.substring(value.length - 2, value.length) === ":1") {
            goodValues.push(readingOrder[index]);
        }
    }

    let result;
    for (index = 0; index < goodValues.length; index++) {
        if ((goodValues[index].substring(0, goodValues[index].indexOf(":"))) == urlParams.get('id')) {
            result = index;
        }
    }
    return ([result, goodValues.length]);
}

function hideButtons() {
    if (filteredSelf()[0] === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (filteredSelf()[0] === (filteredSelf()[1]) - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }

    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    if (findSelf() === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (findSelf() === readingOrder.length - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }
}

hideButtons();

async function jumpTo() {
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
}

function goBack() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber - 1; index < readingOrder.length; index--) {
        if (readingOrder[index].includes(":1")) {
            newID = readingOrder[index].substring(0, readingOrder[index].indexOf(":"));
            window.location.href = ("/read/?id=" + newID + "&lang=" + getOptimalLanguage(newID));
            break;
        }
    }
}

function goForward() {
    // Get language list from localStorage, get available languages for next item.
    // Find highest match.
    // Go.
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber + 1; index < readingOrder.length; index++) {
        if (readingOrder[index].includes(":1")) {
            window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
            break;
        }
    }
}

function savePlace() {
    try {
        localStorage.setItem("savePlace", window.location);
        alert("Your place was saved successfully.")
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

function loadPlace() {
    if (localStorage.getItem("savePlace") === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("savePlace");
    }
}