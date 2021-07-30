async function initialize() {
    if (localStorage.getItem("version") != "1.0") {
        // Try to convert saved place first.
        localStorage.setItem("version", "1.0");
    }

    if ((localStorage.getItem("languagePreference") === null) || (localStorage.getItem("languageList") === null)) {
        // Step 1: Get preferred language.
        const lang = navigator.language;
        localStorage.setItem("languagePreference", lang.substring(0, 2));

        // Step 2: Get all languages and put them in a list.
        languageList = await getLanguageList();
        localStorage.setItem("languageList", languageList);
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
            // Fix on first run.
        } else {
            localStorage.setItem("colorScheme", "dark");
        }
    } else if (localStorage.getItem("colorScheme") === "light") {
        document.getElementById("paletteSwapButton").innerHTML = "☽";
    }

    if (localStorage.getItem("readingOrder:0") === null || localStorage.getItem("version") === null) {
        let recommendedReadingOrder = await getRecommendedReadingOrder();
        localStorage.setItem("readingOrder:0", recommendedReadingOrder);
    }

    if (sessionStorage.getItem("activeReadingOrder") === null) {
        const keyArray = Object.keys(localStorage);
        let readingOrders = keyArray.filter(name => name.includes('readingOrder'));
        if (readingOrders.length == 1) {
            sessionStorage.setItem("activeReadingOrder", readingOrders[0].split(":")[1]);
        }
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

    if (localStorage.getItem("fontSize") === null) {
        localStorage.setItem("fontSize", "normal");
    }
};

function getLanguageList() {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/getlanguagelist.php", true);
        xmlhttp.send();
    });
}

function getRecommendedReadingOrder() {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initread.php", true);
        xmlhttp.send();
    });
}

initialize();

async function resetReader() {
    localStorage.clear();
    let success = await initialize();
    if (success == true) {
        alert("Reset complete.");
    } else {
        alert("ERROR: Reset failed. Please report to admin@wallofhistory.com.")
    }
}

/* READER NAVIGATION HELPERS */

function getOptimalLanguage(combo) {
    return new Promise(resolve => {
        let languageList = localStorage.getItem("languageList").split(",");
        let target = combo.split(".");
        let id = target[0];
        let v = target[1];
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
        xmlhttp.open("GET", "../php/getavailablelanguages.php?id=" + id + "&v=" + v, true);
        xmlhttp.send();
    });
}

function findSelf() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);
    for (index = 0; index < readingOrder.length; index++) {
        let candidate = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[0];
        if (candidate == urlParams.get('id')) {
            result = index;
        }
    }
    return (result);
}

function filteredSelf(readingOrder) {
    // This returns the indexes of the first and last active pages in the reading order.
    let index;
    let returns = [];
    for (index = 0; index < readingOrder.length; index++) {
        let value = readingOrder[index];
        if (value.substring(value.length - 2, value.length) === ":1") {
            returns.push(index);
        }
    }

    return ([returns[0], returns[returns.length - 1]]);
}

function showButtons() {
    let backButton = document.getElementById("backbutton");
    let forwardButton = document.getElementById("forwardbutton");

    if (findSelf() != undefined) {
        // Display save/load place buttons.
        $(document.getElementsByClassName("savefile")[0]).slideDown("slow");

        // Display appropriate navigation buttons.
        let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
        let inners = filteredSelf(readingOrder);

        /**
         * There are five values that are important here:
         * The first item in a reading order, the first active item in that reading order...
         * ...the current place, the last active item, and the last active item.
         * The functions below ensure that you can always navigate the active items.
         * If you're within the "inner start" and "inner end," both nav buttons appear.
         * If you're outside of that range, only one nav button will appear —
         * — whichever one will get you closer to the active range.
         */
        if (findSelf() <= inners[0]) {
            forwardButton.style.display = "block";
            $(document.getElementsByClassName("nav")[0]).slideDown("slow");
        }
        if (findSelf() >= inners[1]) {
            backButton.style.display = "block";
            $(document.getElementsByClassName("nav")[0]).slideDown("slow");
        }
        if ((inners[0] < findSelf()) && (findSelf() < inners[1])) {
            backButton.style.display = "block";
            forwardButton.style.display = "block";
            $(document.getElementsByClassName("nav")[0]).slideDown("slow");
        }
    } else {
        document.getElementsByClassName("savefile")[0].remove();
        document.getElementsByClassName("nav")[0].remove();
    }
}

let showInterval = window.setInterval(showCallback, 500);

function showCallback() {
    // Find a better way to do this (probably involving async functions).
    showButtons();
}

/* LANGUAGE STUFF (INCOMPLETE) */

let full = localStorage.getItem("languageList").split(",");
let available = document.getElementsByTagName("section");

if (available.length >= 1) {
    let a = []
    for (i = 0; i < available.length; i++) {
        a.push(available[i].lang);
    }

    let intersection = full.filter(x => a.includes(x));

    $("section:lang(" + intersection[0] + ")").css("display", "block");
}

/* READER NAVIGATION */

async function goTo(combo) {
    target = combo.split(".");
    if (target.length == 2) {
        let id = target[0];
        let v = target[1];
        let lang = await getOptimalLanguage(combo);
        window.location.href = ("/read/?id=" + id + "&v=" + v + "&lang=" + lang);
    } else if (target.length == 3) {
        let id = target[0];
        let v = target[1];
        let lang = target[2];
        window.location.href = ("/read/?id=" + id + "&v=" + v + "&lang=" + lang);
    } else {
        alert("ERROR: Redirection failed. Please report to admin@wallofhistory.com.");
        window.location.href = "/";
    }
}

async function jumpTo() {
    if (sessionStorage.getItem("activeReadingOrder") === null) {
        generateSelectionModal();
    } else if ((sessionStorage.getItem("activeReadingOrder") != null) && (localStorage.getItem("savePlace:" + (sessionStorage.getItem("activeReadingOrder"))) === null)) {
        let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
        let index, value;
        for (index = 0; index < readingOrder.length; ++index) {
            value = readingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                result = value;
                newID = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[0];
                newVersion = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[1];
                newLang = await getOptimalLanguage(readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                window.location.href = ("/read/?id=" + newID + "&v=" + newVersion + "&lang=" + newLang);
                break;
            }
        }
    } else {
        window.location.href = localStorage.getItem("savePlace:" + (sessionStorage.getItem("activeReadingOrder")));
    }
}

async function goBack() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber - 1; index < readingOrder.length; index--) {
        if (readingOrder[index].includes(":1")) {
            next = readingOrder[index].split(":")[0];
            goTo(next);
            break;
        }
    }
}

async function goForward() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber + 1; index < readingOrder.length; index++) {
        if (readingOrder[index].includes(":1")) {
            next = readingOrder[index].split(":")[0];
            goTo(next);
            break;
        }
    }
}

function savePlace() {
    try {
        localStorage.setItem("savePlace:" + sessionStorage.getItem("activeReadingOrder"), window.location);
        alert("Your place was saved successfully.")
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

function loadPlace() {
    if (localStorage.getItem("savePlace:" + sessionStorage.getItem("activeReadingOrder")) === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("savePlace:" + sessionStorage.getItem("activeReadingOrder"));
    }
}

/* READ AS STANDALONE */

function readAsStandaloneSetup(id) {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initreadstandalone.php?id=" + id + "&v=1", true);
        xmlhttp.send();
    });
}

async function readAsStandalone() {
    let currentID = new URLSearchParams(window.location.search).get("id");
    let newOrder = await readAsStandaloneSetup(currentID);
    localStorage.setItem("readingOrder:" + currentID, newOrder);
    sessionStorage.setItem("activeReadingOrder", currentID);
    jumpTo();
}

/* DOWNLOAD FUNCTIONS */

function downloadContent() {
    const urlParams = new URLSearchParams(window.location.search);
    let currentID = urlParams.get("id");

    $.get("/doc/downloads/" + currentID + ".zip")
        .done(function() {
            document.getElementById("downloadLink").href = "/doc/downloads/" + currentID + ".zip";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    filename = this.responseText.replace(/<\/?[^>]+(>|$)/g, "") + ".zip"
                        // Fix the above regex to strip to alphanumeric.
                        // Other than that... this still works! Nice.
                    document.getElementById("downloadLink").download = filename;
                }
            };
            xmlhttp.open("GET", "../php/gettitle.php?q=" + currentID, true);
            xmlhttp.send();
            $(document.getElementById("downloadLink")).fadeIn("slow");
        }).fail(function() {
            console.log("No downloads are available for this content.");
        })
}