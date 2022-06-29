/***********************
 * UNIVERSAL FUNCTIONS *
 ***********************/


// This function loads a given cookie.
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return null;
}


// This function loads the configuration data from config.json.
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


function hideShow(button) {
    if (button.parentNode.tagName == "ASIDE") {
        let nextSibling = button.nextElementSibling;
        let currentStyles = window.getComputedStyle(nextSibling);
        if (currentStyles.display === "none" || currentStyles.display === "") {
            while (nextSibling) {
                $(nextSibling).slideDown();
                nextSibling = nextSibling.nextElementSibling;
            }
            let oldText = button.innerText;
            button.innerText = oldText.replace("⮞", "⮟");
        } else {
            while (nextSibling) {
                $(nextSibling).slideUp();
                nextSibling = nextSibling.nextElementSibling;
            }
            let oldText = button.innerText;
            button.innerText = oldText.replace("⮟", "⮞");
        }
    } else {
        let buttons = document.getElementById("modal-data").getElementsByClassName("hideShow");
        let texts = document.getElementById("modal-data").getElementsByClassName("showable");
        let buttonIndex = Array.prototype.indexOf.call(buttons, button);
        let currentStyles = window.getComputedStyle(texts[buttonIndex]);
        if (currentStyles.display === "none" || currentStyles.display === "") {
            $(texts[buttonIndex]).slideDown();
            let oldText = button.innerText;
            button.innerText = oldText.replace("⮞", "⮟");
        } else {
            $(texts[buttonIndex]).slideUp();
            let oldText = button.innerText;
            button.innerText = oldText.replace("⮟", "⮞");
        }
    }
}


/**
 * INITIALIZATION FUNCTIONS
 */


// This function checks if a reading mode is set (and if not, sets it).
async function checkReadingMode() {
    if (localStorage.getItem("readingMode") === null) {
        loadJSON(async function (response) {
            let readingMode = JSON.parse(response).readingorder;
            localStorage.setItem("readingMode", readingMode);
        });
    }
}


// This function loads a list of languages available on the site from the server.
// Helper for checkLanguage().
async function getLanguageList() {
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


// This function checks if a language preferences are set (and if not, sets them).
async function checkLanguage() {
    if ((localStorage.getItem("languagePreference") === null) || (localStorage.getItem("languageList") === null)) {
        // Step 1: Get all languages and put them in a list.
        languageList = await getLanguageList();
        localStorage.setItem("languageList", languageList);

        // Step 2: Get preferred language.
        const lang = navigator.language.substring(0, 2);
        localStorage.setItem("languagePreference", lang);
        if ((getCookie("languagePreference") == null) || (getCookie("languagePreference") != lang)) {
            document.cookie = "languagePreference=" + lang + "; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        }
    } else {
        if ((getCookie("languagePreference") == null) || (getCookie("languagePreference") != localStorage.getItem("languagePreference"))) {
            document.cookie = "languagePreference=" + localStorage.getItem("languagePreference") + "; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        }
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
}


// This function ensures that the color scheme applied to the page is correct (though this should now be handled serverside).
async function checkColorScheme() {
    if (localStorage.getItem("colorScheme") === null) {
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches) {
            localStorage.setItem("colorScheme", "light");
            document.cookie = "colorPreference=light; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
            swapPalettes();
        } else {
            localStorage.setItem("colorScheme", "dark");
            document.cookie = "colorPreference=dark; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        }
    }

    if (getCookie("colorPreference") === null) {
        console.log("Logging color scheme preference…");
        document.cookie = "colorPreference=" + localStorage.getItem("colorScheme") + "; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
    }
}


// Helper for checkReadingOrder().
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


// Helper for checkReadingOrder().
async function setReadingOrder(newReadingOrder) {
    localStorage.setItem("readingOrder:0", newReadingOrder);
}


// This function checks if the default reading order is set (and if not, sets it).
async function checkReadingOrder() {
    if (localStorage.getItem("readingOrder:0") === null || localStorage.getItem("version") === null) {
        loadJSON(async function (response) {
            let recommendedReadingOrder = await getRecommendedReadingOrder(JSON.parse(response).readingorder);
            setReadingOrder(recommendedReadingOrder).then(() => {
                let homepageReadButtons = document.getElementsByClassName("homepageReadButton");
                for (let i = 0; i < homepageReadButtons.length; i++) {
                    homepageReadButtons[i].disabled = false;
                }
                console.log("Reading order ready.");
            });
        });
    } else {
        activateNavigation();
    }
}


// If the user only has one reading order in their storage, this function sets it to active no matter what.
async function activateReadingOrder() {
    if (sessionStorage.getItem("activeReadingOrder") === null) {
        const keyArray = Object.keys(localStorage);
        let readingOrders = keyArray.filter(name => name.includes('readingOrder'));
        if (readingOrders.length == 1) {
            sessionStorage.setItem("activeReadingOrder", readingOrders[0].split(":")[1]);
        }
    }
}


// This function sets the default spoiler level.
async function checkSpoilerLevel() {
    if (localStorage.getItem("spoilerLevel") === null) {
        localStorage.setItem("spoilerLevel", "1");
    }
}


// This function loads the full list of reference terms from the database and stores it in localStorage.
async function checkReferenceTerms() {
    const query = "SELECT GROUP_CONCAT(DISTINCT title SEPARATOR ',') AS titles FROM reference_titles";

    if (localStorage.getItem("referenceTerms") === null) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("referenceTerms", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/query.php?q=" + query + "&c=titles", true);
        xmlhttp.send();
    }
}


// This function sets the default font size.
async function checkFontSize() {
    if (localStorage.getItem("fontSize") === null) {
        localStorage.setItem("fontSize", "normal");
    }
}


// This function sets the site version.
async function checkVersion() {
    if (localStorage.getItem("version") != "1.1") {
        localStorage.setItem("version", "1.1");
    }
}


// If the user has all the data in their storage to run the reader page, this function activates the navigation button.
async function activateNavigation() {
    let homepageReadButtons = document.getElementsByClassName("homepageReadButton");
    for (let i = 0; i < homepageReadButtons.length; i++) {
        homepageReadButtons[i].disabled = false;
    }
    console.log("Reader ready.");
}


// Initialization function which runs all the functions above.
async function initialize() {
    await checkReadingMode();
    await checkLanguage();
    await checkColorScheme();
    await checkReadingOrder();
    await activateReadingOrder();
    await checkSpoilerLevel();
    await checkReferenceTerms();
    await checkFontSize();
    await checkVersion();
};


initialize();


// Resets the user's local storage data to default values.
async function resetReader() {
    localStorage.clear();
    initialize().then(() => {
        alert("Reader reset.");
    });
}


function errorMessage(message) {
    alert(message);
}


/**
 * END INITIALIZATION FUNCTIONS
 */


/***************************
 * END UNIVERSAL FUNCTIONS *
 ***************************/


/*****************************
 * READER NAVIGATION HELPERS *
 *****************************/


// This function returns the best language available for a given story entry, based on the user's language preferences.
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


/*********************************
 * END READER NAVIGATION HELPERS *
 *********************************/


/************************************************
 * CHRONOLOGY-BASED READER NAVIGATION FUNCTIONS *
 ************************************************/


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


/* This function essentially determines if the current page is a leaf (i.e., a page on which the navigation buttons /might/ appear). */
function findSelfAbsolute(id) {
    let fullReadingOrder = localStorage.getItem("readingOrder:0");
    if (fullReadingOrder.includes(id)) {
        return true;
    } else {
        return false;
    }
}


/* This function returns the indexes of the first and last selected pages in the active reading order. */
function getEndPoints(readingOrder) {
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


/* This function takes an ID, version (optional, but highly encouraged), and language (optional), and jumps straight to that page. */
async function goToChrono(combo) {
    target = combo.split(".");
    if (target.length == 1) {
        let id = target[0];
        let v = "1";
        let lang = await getOptimalLanguage(id + "." + v);
        window.location.href = ("/read/?id=" + id + "&v=" + v + "&lang=" + lang);
    } else if (target.length == 2) {
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
        try {
            window.location.href = localStorage.getItem("savePlace:" + (sessionStorage.getItem("activeReadingOrder")));
        } catch (err) {
            alert("ERROR: Redirection failed. Please try reloading the page. If this error persists, report to admin@wallofhistory.com.")
        }
    }
}


/* This function shows the relevant navigation buttons for a given page, relative to the active reading order. */
function showButtonsChrono() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const currentID = urlParams.get('id');

    if (document.getElementById("backbutton") !== null) {
        let backButton = document.getElementById("backbutton");
        let disambiguationButton = document.getElementById("disambiguationbutton");
        let forwardButton = document.getElementById("forwardbutton");

        // Why is this not working on that one Wall of History (BIONICLE.com) page?
        if (findSelfAbsolute(currentID)) {
            if (sessionStorage.getItem("activeReadingOrder") == null) {
                disambiguationButton.style.display = "block";
                $(document.getElementsByClassName("nav")[0]).slideDown("slow");
            } else {
                // Display save/load place buttons.
                $(document.getElementsByClassName("savefile")[0]).slideDown("slow");

                // Display appropriate navigation buttons.
                let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
                let inners = getEndPoints(readingOrder);

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
        jumpToChrono();
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
            goToChrono(next);
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
            goToChrono(next);
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


/* Calls readAsStandaloneSetup(), sets necessary localStorage variables, and jumps the user to the first page of the selected story. */
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


/*************************************************
 * SIMPLE TREE-BASED READER NAVIGATION FUNCTIONS *
 *************************************************/


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
    window.location.href = "/read/?id=" + siblings[position - 1];
}


/* This function navigates the user to the next selected page in the current array. */
async function goForwardTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArrayTree();
    siblings = siblings.split(",");
    let position = siblings.indexOf(id);
    window.location.href = "/read/?id=" + siblings[position + 1];
}


/*****************************************************
 * END SIMPLE TREE-BASED READER NAVIGATION FUNCTIONS *
 *****************************************************/


/********************
 * MASTER FUNCTIONS *
 ********************/


function goTo(combo) {
    if (localStorage.getItem("readingMode") == "chronology") {
        goToChrono(combo);
    } else {
        alert("ERROR: Navigation failed. Please report to admin@wallofhistory.com.")
    }
}

function jumpTo() {
    if (localStorage.getItem("readingMode") == "chronology") {
        jumpToChrono();
    } else {
        jumpToTree();
    }
}

function savePlace() {
    if (localStorage.getItem("readingMode") == "chronology") {
        savePlaceChrono();
    } else {
        savePlaceTree();
    }
}

function loadPlace() {
    if (localStorage.getItem("readingMode") == "chronology") {
        loadPlaceChrono();
    } else {
        loadPlaceTree();
    }
}

function goBack() {
    if (localStorage.getItem("readingMode") == "chronology") {
        goBackChrono();
    } else {
        goBackTree();
    }
}

function goForward() {
    if (localStorage.getItem("readingMode") == "chronology") {
        goForwardChrono();
    } else {
        goForwardTree();
    }
}

function showButtons() {
    if (localStorage.getItem("readingMode") == "chronology") {
        showButtonsChrono();
    } else {
        showButtonsTree();
    }
}


/************************
 * END MASTER FUNCTIONS *
 ************************/


/********************
 * MODULE FUNCTIONS *
 ********************/


// These can be tested on “The Legend of Mata Nui,” which has three parents.
// Make these update CSS where applicable: https://stackoverflow.com/questions/574944/how-to-load-up-css-files-using-javascript
function carouselForward(button) {
    let parents = button.parentElement.querySelectorAll("h1, h3");
    for (let i = 0; i < parents.length; i++) {
        let currentStyles = window.getComputedStyle(parents[i]);
        if ((currentStyles.display != "none") && (i < parents.length - 1)) {
            parents[i].style.display = "none";
            parents[i + 1].style.display = "block";
            break;
        } else if ((currentStyles.display != "none") && (i == parents.length - 1)) {
            parents[i].style.display = "none";
            parents[0].style.display = "block";
            break;
        }
    }
}


function carouselBack(button) {
    let parents = button.parentElement.querySelectorAll("h1, h3");
    for (let i = 0; i < parents.length; i++) {
        let currentStyles = window.getComputedStyle(parents[i]);
        if ((currentStyles.display != "none") && (i > 0)) {
            parents[i].style.display = "none";
            parents[i - 1].style.display = "block";
            break;
        } else if ((currentStyles.display != "none") && (i == 0)) {
            parents[i].style.display = "none";
            parents[parents.length - 1].style.display = "block";
            break;
        }
    }
}


/************************
 * END MODULE FUNCTIONS *
 ************************/


/*******************
 * IMAGE FUNCTIONS *
 *******************/


function addZoomEventListeners() {
    let images = document.querySelectorAll(".story > img, .story > section > img, .mediaplayercontents > img");
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const id = urlParams.get('id');

    // Will need to do all this each time the modal loads.

    for (var i = 0; i < images.length; i++) {
        images[i].addEventListener("click", function () {
            let src = this.src;
            let alt = this.alt;
            let img = document.createElement("img");
            img.setAttribute("src", src);

            let zoom = document.createElement("div");
            zoom.classList.add("zoom");

            document.body.appendChild(zoom);

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let check = this.responseText;
                    if (check != "false") {
                        dimensions = check.split(",");
                        console.log(dimensions);
                        init3D(dimensions[0], dimensions[1], dimensions[2], id);
                    } else {
                        zoom.appendChild(img);
                        if (alt != ("img" || "" || null)) {
                            let caption = document.createElement("p");
                            caption.innerHTML = alt;
                            zoom.appendChild(caption);
                        }
                    }
                }
            };
            xmlhttp.open("GET", "/php/check3d.php?id=" + id, true);
            xmlhttp.send();

            let exit = document.createElement("span");
            exit.classList.add("exitSpan");
            exit.setAttribute("onclick", "closeImage();");
            exit.innerHTML = "🞮";
            zoom.appendChild(exit);
            $(".zoom").fadeTo("fast", 1);
        });
    }
}


document.onload = addZoomEventListeners();


function closeImage() {
    $(".zoom").fadeOut("fast");
    setTimeout(() => {
        $(".zoom").remove();
    }, 200);
}


window.onclick = function (event) {
    if (event.target.classList.contains('zoom')) {
        closeImage();
    } else if (event.target.classList.contains('modal')) {
        generalToggle();
    }
}


document.addEventListener('keydown', function (event) {
    if (event.key === "Escape") {
        closeImage();
    }
});


/***********************
 * END IMAGE FUNCTIONS *
 * *********************/