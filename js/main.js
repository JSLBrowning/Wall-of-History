/***********************
 * UNIVERSAL FUNCTIONS *
 ***********************/

/* This function loads the configuration data from config.json. */
function loadJSON(callback) {
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
    xobj.open('GET', '/config/config.json', true);
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            callback(xobj.responseText);
        }
    };
    xobj.send(null);
}


async function initialize() {
    /* Add code to use loadJSON and store config data in localStorage. */
    if (localStorage.getItem("WallofHistorySavePlace") != null) {
        let oldSavePlace = localStorage.getItem("WallofHistorySavePlace");
        localStorage.clear();
        localStorage.setItem("savePlace:0", oldSavePlace);
    }

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
        languageList.sort(function (x, y) {
            return x == preferred ? -1 : y == preferred ? 1 : 0;
        });
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
        loadJSON(async function (response) {
            let recommendedReadingOrder = await getRecommendedReadingOrder(JSON.parse(response).readingorder);
            setReadingOrder(recommendedReadingOrder).then(() => {
                document.getElementById("homepageReadButton").disabled = false;
                console.log("Reader ready.");
            });
        });
    } else {
        if ($("#homepageReadButton").length > 0) {
            document.getElementById("homepageReadButton").disabled = false;
        }
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
        xmlhttp.onreadystatechange = function () {
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

    if (localStorage.getItem("languagePreference") !== null) {
        document.cookie = "languagePreference=" + localStorage.getItem("languagePreference") + "; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
    }
};

function getLanguageList() {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/getlanguagelist.php", true);
        xmlhttp.send();
    });
}

function getRecommendedReadingOrder(type) {
    if (type == "chronology") {
        return new Promise(resolve => {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    resolve(this.responseText);
                }
            };
            xmlhttp.open("GET", "../php/initread.php", true);
            xmlhttp.send();
        });
    } else {
        return "null";
    }
}

async function setReadingOrder(newReadingOrder) {
    localStorage.setItem("readingOrder:0", newReadingOrder);
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

/*
 * READER NAVIGATION HELPERS
 */

function getOptimalLanguage(combo) {
    return new Promise(resolve => {
        let languageList = localStorage.getItem("languageList").split(",");
        let target = combo.split(".");
        let id = target[0];
        let v = target[1];
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
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

/*********************************************************
 * CHRONOLOGY-BASED READER NAVIGATION FUNCTIONS (WALL OF HISTORY VERSIONS) *
 *********************************************************/

/* This function finds the index of the current page in the active reading order. */
function findSelf() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);
    let currentID = document.getElementById("downloadMarker").innerHTML;
    for (index = 0; index < readingOrder.length; index++) {
        let candidate = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[0];
        if (candidate == currentID) {
            result = index;
        }
    }
    return (result);
}

/* This function returns the indexes of the first and last selected pages in the active reading order. */
function filteredSelf(readingOrder) {
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

/* This function takes an ID, version, and language (optional) and jumps straight to that page. */
async function goToChrono(combo) {
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

/* This function attempts to jump the user back to their most recent spot,
prompting them to select a reading order if necessary. */
async function jumpToChrono() {
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

/* This function shows the relevant navigation buttons for a given page,
relative to the active reading order. */
function showButtonsChrono() {
    if (document.getElementById("backbutton") !== null) {
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
    } else {
        console.log("No back/forward buttons found.");
        window.clearInterval(showInterval);
    }
}

// This function ensures the above function eventually completes correctly.
let showInterval = window.setInterval(showChronoCallback, 500);
function showChronoCallback() {
    // Find a better way to do this (probably involving async functions).
    // This might be necessary for the... reading order select thing.
    // If no reading order is selected, one NEEDS to be selected, THEN this can run.
    showButtonsChrono();
}

/* This function saves a user’s place for the active reading order. */
function savePlaceChrono() {
    try {
        localStorage.setItem("savePlace:" + sessionStorage.getItem("activeReadingOrder"), window.location);
        alert("Your place was saved successfully.")
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

/* This function attempts to jump the user back to their current saved place. */
function loadPlaceChrono() {
    if (localStorage.getItem("savePlace:" + sessionStorage.getItem("activeReadingOrder")) === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("savePlace:" + sessionStorage.getItem("activeReadingOrder"));
    }
}

/* This function navigates the user to the previous selected page in the active reading order. */
async function goBackChrono() {
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

/* This function navigates the user to the next selected page in the active reading order. */
async function goForwardChrono() {
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

/* Helper function for readAsStandalone (interfaces with necessary backend code). */
function readAsStandaloneSetup(id) {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initreadstandalone.php?id=" + id + "&v=1", true);
        xmlhttp.send();
    });
}

/* Calls readAsStandaloneSetup(), sets necessary localStorage variables, and jumps the user to the first page. */
async function readAsStandalone() {
    let currentID = document.getElementById("downloadMarker").innerHTML;
    let newOrder = await readAsStandaloneSetup(currentID);
    localStorage.setItem("readingOrder:" + currentID, newOrder);
    sessionStorage.setItem("activeReadingOrder", currentID);
    jumpTo();
}

/****************************************************
 * END CHRONOLOGY-BASED READER NAVIGATION FUNCTIONS *
 ****************************************************/


/*****************************************************************************
 * SIMPLE TREE-BASED READER NAVIGATION FUNCTIONS (MYTHS AND LEGACY VERSIONS) *
 *****************************************************************************/

/* This function gets an array of the siblings of the current page. */
function getArrayTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");

    return new Promise(resolve => {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "/php/getarray.php?q=" + id, true);
        xmlhttp.send();
    });
}

/* This function finds the index of the current page in the aforementioned array. */
async function findSelfTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArrayTree();

    return siblings.indexOf(id);
}

/* This function shows the relevant navigation buttons for a given page. */
async function showButtonsTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArrayTree();
    siblings = siblings.split(",");

    if (id == siblings[0]) {
        let saveButtons = document.getElementsByClassName("savefile");
        saveButtons[0].style.display = "flex";
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "block";
    } else if (id == siblings[siblings.length - 1]) {
        let saveButtons = document.getElementsByClassName("savefile");
        saveButtons[0].style.display = "flex";
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "block";
    } else if (siblings.includes(id)) {
        let saveButtons = document.getElementsByClassName("savefile");
        saveButtons[0].style.display = "flex";
        let backButton = document.getElementById("backbutton");
        let forwardButton = document.getElementById("forwardbutton");
        backButton.style.display = "block";
        forwardButton.style.display = "block";
    }
}

/* This function is called when the page is loaded.
Uh… Find a better option than just calling it raw, I guess. */
showButtonsTree();

/* This function saves the user’s place.
TO-DO: Make this parent-specific, and add a selector if the load button is used on the homepage. */
function savePlaceTree() {
    localStorage.setItem("MythsandLegacySavePlace", window.location);
    alert("Your place was saved successfully.");
}

/* This function attempts to jump the user back to their most recent spot.
TO-DO: Make this parent-specific, and add a selector is the load button is used on the homepage.
Also, add an option for users to “finish” things and clear them from the reading orders. */
function jumpToTree() {
    if (localStorage.getItem("MythsandLegacySavePlace") === null) {
        window.location.pathname = "/read";
    } else {
        window.location.href = localStorage.getItem("MythsandLegacySavePlace");
    }
}

/* This function attempts to jump the user back to their current saved place.
TO-DO: Make this parent-specific, and add a selector is the load button is used on the homepage. */
function loadPlaceTree() {
    if (localStorage.getItem("MythsandLegacySavePlace") === null) {
        jumpToTree();
    } else {
        window.location.href = localStorage.getItem("MythsandLegacySavePlace");
    }
}

/* This function navigates the user to the previous selected page in the current array. */
async function goBackTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArrayTree();
    siblings = siblings.split(",");
    let position = siblings.indexOf(id);
    window.location.href = "/read/?id=" + siblings[position-1];
}

/* This function navigates the user to the next selected page in the current array. */
async function goForwardTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArrayTree();
    siblings = siblings.split(",");
    let position = siblings.indexOf(id);
    window.location.href = "/read/?id=" + siblings[position+1];
}

/*****************************************************
 * END SIMPLE TREE-BASED READER NAVIGATION FUNCTIONS *
 *****************************************************/

/**
MnL:
  getArray
  findSelf
  showButtons (autorun)
  savePlace
  jumpTo
  loadPlace
  goBack
  goForward


altered WoH:
  goTo
  jumpTo
  goBack
  goForward
  savePlace
  loadPlace
 */