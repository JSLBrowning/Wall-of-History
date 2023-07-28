// What do we do on first load?
// 1. Check user's browser for color scheme preference. If present, save to cookie. Otherwise, set to dark.
// 2. Check user's browser for language preference. If present, save to cookie. Otherwise, set to... "en"?


// How do we determine a route?
// 1. Buttons for routes will have an activateRoute function. Carries a route ID.
// 2. On click, a saveplace for the route in question will be checked. If it exists, jump to that page.
// 3. Else, jump to first page in route.


// What do we do on reader load?
// 1. Check if an active route is set. If not, and all children have chronology values, assume parent route.
// 2. If so, check if the current page is in the active route. If not...
//    a. If the PARENT of the current page is, assume A->B->C is now A->B1_1->B1_2->C, based on chronology of children.
//    b. If not, clear active route.
// 3. If the current page IS in the active route, activate buttons accordingly.
// 4. If you CAME from a parent work in the table of contents, assume parent route.


// Where can you come from?
// 1. External link. Fully off-site. Assume parent route if self and siblings have chronology, no route otherwise.
//    a. Use version 1 if no version specified.
//    b. Get browser language for language. Default to English if not available.
// 2. Table of contents. Parent work. Assume parent route if self and siblings have chronology, no route otherwise.
//    a. Version and language are set by buttons.
// 3. "Start Reading" button. Assume specified route if passed in button activation, parent route if self and siblings have chronology but no route was passed, no route otherwise.


// Function could be called... "goRead()"? Required param: ID. Optional params: route ID, version, language. If no route ID, check for chronology. If no chronology, no route. If no version, use... 1. If no language, check if available in user language, else, whatever is at the top of the list. English if available.


/************************ FUNCTION GRAVEYARD ************************
 * An unnamed function to check if the current ID is in the active reading order, clearing it otherwise. Called on every load.
 * hideShow(button) - A function to hide or show the main menu on mobile. No longer using <aside> tag.
 * checkReadingMode() - A function to set the reading mode based on the config file. Reading modes are no longer used.
 * getRecommendedReadingOrder() - A function to get the default reading order. There is no longer a default reading order.
 * setReadingOrder() - A function to save the above to localStorage. No longer needed.
 * checkReadingOrder() - A function to check if the above is set. No longer needed.
 * activateReadingOrder() - A function to activate the default reading order if no other is found. No longer needed.
 * activateNavigation() - A function to activate reader buttons if all needed data was present. That data is no longer needed.
 * resetReader() - A function to reset products of above to default. No longer needed.
 * readAsStandalone() - A function to generate a new reading order based on the current page. No longer needed.
 * readAsStandaloneSetup() - Helper function for above. No longer needed.
 * check() - Attempted to set the right reading order from a reader page if none had been set.
 * zoomExtras() - Generated new layer for extras. Deprecated as extras will now be modal articles.
 * stackHistory() - Stored a stack of recently-visited IDs for theme disambiguation. No longer needed.
 ********************************************************************/


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


// Function to take a JSON object and find all elements where the passed ID is in the current field.
function findInJSON(json, id) {
    console.log("WIP.");
}


// This function loads the data (by default the global config params) from a JSON file.
function loadJSONFromPath(path = "/config/config.json", callback) {
    var xobj = new XMLHttpRequest();
    xobj.overrideMimeType("application/json");
    xobj.open('GET', path, true);
    xobj.onreadystatechange = function () {
        if (xobj.readyState == 4 && xobj.status == "200") {
            callback(xobj.responseText);
        }
    };
    xobj.send(null);
}


/***************************
 * NEW FUNCTION PLAYGROUND *
 ***************************/


function queryDatabase(sqlQuery, sqlColumn) {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/query.php?q=" + sqlQuery + "&c=" + sqlColumn, true);
        xmlhttp.send();
    });
}


async function read(routeID = null, routePath = null) {
    // Check if there is a savePlace:routeID in localStorage. If so, go to that page.
    if (localStorage.getItem("savePlace:" + routeID) !== null) {
        window.location.href = localStorage.getItem("savePlace:" + routeID);
    }

    // If not, check if routePath is set. If so, parse that JSON file.
    if (routePath == null) {
        let sqlQuery = "SELECT route_main FROM shin_routes WHERE route_id = \"" + routeID + "\"";
        let sqlColumn = "route_main";
        let queryResult = await queryDatabase(sqlQuery, sqlColumn);
        if (queryResult == "") {
            console.log("No route found.");
        }
        let route = JSON.parse(queryResult);
        console.log(route);
        console.log(route.length);
    }
}


function getFirstPage(route) {
    for (let i = 0; i < route.length; i++) {
        if (route[i].current !== undefined) {
            return route[i].current;
        } else {
            if (route[i].children !== undefined) {
                getFirstPage(route[i].children);
            }
        }
    }
}


async function startRoute(routeID) {
    // Check if the route is in the database.
    let sqlQuery = "SELECT route_main FROM shin_routes WHERE route_id = \"" + routeID + "\" LIMIT 1";
    let sqlColumn = "route_main";
    let queryResult = await queryDatabase(sqlQuery, sqlColumn);

    if (queryResult == "") {
        alert("No route was found. Please report this error to admin@wallofhistory.com");
    } else {
        let route = JSON.parse(queryResult);
        let firstPage = getFirstPage(route);
        
        let firstID = firstPage.id;
        // If first page has version, use that, otherwise, use 1.
        let firstVersion = firstPage.version || 1;
        // If first page has language, use that, otherwise, use "en."
        let firstLanguage = firstPage.language || "en";

        window.location.href = "../read/?id=" + firstID + "&version=" + firstVersion + "&language=" + firstLanguage;
    }
}


// startRoute("d9669c6a-d648-11ed-beaa-00ff2a5c27e8");


function readNuva(route=null, id=null, version=null, language=null) {
    // If all four inputs are null, check the config JSON object for the mainWork.
    if ((route == null) && (id == null) && (version == null) && (language == null)) {
        // If there is no mainWork, return an error.
        if (config.mainWork == null) {
            alert("Error: No main work specified in config file.");
            return;
        }
        // If there is a mainWork, set the route to that.
        else {
            route = config.mainWork;
        }
    }

    // If route is set, check if there is a savePlace for it. If so, go to that page.
    if (route !== null) {
        if (localStorage.getItem("savePlace:" + route) !== null) {
            window.location.href = localStorage.getItem("savePlace:" + route);
        }
    }

    // If ID is null, assume we're STARTING a route.
    if (id == null) {
        startRoute(routeID);
    } else {
        // Else, set sessionStorage.activeRoute to routeID, then jump to the page.
        sessionStorage.activeRoute = routeID;
        
        // Get ideal language if language is null.
        if (language == null) {
            console.log("Working on it.");
        }
    }
}


/**
 * INITIALIZATION FUNCTIONS
 */


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
        xmlhttp.open("GET", "../php/getters/getLanguageList.php", true);
        xmlhttp.send();
    });
}


// This function checks if a language preferences are set (and if not, sets them).
async function checkLanguage() {
    if ((localStorage.getItem("languagePreference") === null) || (localStorage.getItem("languageList") === null)) {
        // Step 1: Get all languages and put them in a list.
        let languageList = await getLanguageList();
        localStorage.setItem("languageList", languageList);

        // Step 2: Get preferred language.
        const lang = update_iso_codes(navigator.language.substring(0, 2));
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
                if (this.responseText != "") {
                    localStorage.setItem("referenceTerms", this.responseText);
                }
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
    if (localStorage.getItem("version") != "1.2") {
        localStorage.setItem("version", "1.2");
    }
}


// Initialization function which runs all the functions above.
async function initialize() {
    if (localStorage.getItem("version") != "1.2") {
        localStorage.clear();
    }

    // If languageList contains the word "ERROR," clear.
    if (localStorage.getItem("languageList") != null && localStorage.getItem("languageList").includes("ERROR")) {
        localStorage.clear();
    }

    await checkLanguage();
    // await checkColorScheme();
    await checkSpoilerLevel();
    await checkReferenceTerms();
    await checkFontSize();
    await checkVersion();
};


initialize();


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
        xmlhttp.open("GET", "../php/getters/getAvailableLanguages.php?id=" + id + "&v=" + v, true);
        xmlhttp.send();
    });
}


/*********************************
 * END READER NAVIGATION HELPERS *
 *********************************/


/************************
 * NAVIGATION FUNCTIONS *
 ************************/


/* This function finds the index of the current page in the active reading order. */
function findSelf() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);
    const currentID = urlParams.get('id');
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


/* This function attempts to jump the user back to their most recent spot,
prompting them to select a reading order if necessary. */
async function jumpToChrono() {
    let routeCount = 0;
    for (let key in localStorage) {
        if (key.includes("readingOrder")) {
            routeCount++;
        }
    }

    if (routeCount == 1) {
        sessionStorage.setItem("activeReadingOrder", "0");
    }

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


/* This function gets an array of the siblings of the current page. */
function getArrayTree() {
    return new Promise(resolve => {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "/php/getters/getSiblings.php?q=" + id, true);
        xmlhttp.send();
    });
}


/* This function finds the index of the current page in the aforementioned array. */
async function findSelfTree() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArrayTree(id);

    return siblings.indexOf(id);
}


/* This function determines if it’s appropriate to show the save/load buttons. */
async function showSavePlaceButtons() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let childCount = parseInt(await getNumChildren(id));
    if (childCount == 0) {
        let saveButtons = document.getElementsByClassName("savefile");
        saveButtons[0].style.display = "flex";
    }
}


/* This function takes an ID, version (optional, but highly encouraged), and language (optional), and jumps straight to that page. */
async function goTo(combo) {
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

function goBack() {
    if (window.location.href.includes("reference")) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let page = urlParams.get("pg");
        if (page != null) {
            if (page == "2") {
                window.location.href = "/reference/subjects/";
            } else {
                window.location.href = "/reference/subjects/?pg=" + (parseInt(page) - 1);
            }
        }
    } else if (window.location.href.includes("read")) {
        if (localStorage.getItem("readingMode") == "chronology") {
            goBackChrono();
        } else {
            goBackTree();
        }
    }
}

function goForward() {
    if (window.location.href.includes("reference")) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let page = urlParams.get("pg");
        if (page != null) {
            window.location.href = "/reference/subjects/?pg=" + (parseInt(page) + 1);
        } else {
            window.location.href = "/reference/subjects/?pg=2";
        }
    } else if (window.location.href.includes("read")) {
        if (localStorage.getItem("readingMode") == "chronology") {
            goForwardChrono();
        } else {
            goForwardTree();
        }
    }
}


/****************************
 * END NAVIGATION FUNCTIONS *
 ****************************/


/********************
 * MODULE FUNCTIONS *
 ********************/


// These can be tested on “The Legend of Mata Nui,” which has three parents.
// Make these update CSS where applicable: https://stackoverflow.com/questions/574944/how-to-load-up-css-files-using-javascript
function carouselForward(button) {
    let parents = button.parentElement.querySelectorAll("h2");
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
}s


function carouselBack(button) {
    let parents = button.parentElement.querySelectorAll("h2");
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
    let images = document.querySelectorAll(".story > img, .story > section > img, .mediaplayercontents > img, .mediaplayercontents > span > img");
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
            xmlhttp.open("GET", "/php/getters/get3D.php?id=" + id, true);
            xmlhttp.send();

            let exit = document.createElement("span");
            exit.classList.add("exitSpan");
            exit.setAttribute("onclick", "closeImage();");
            exit.innerHTML = "";
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