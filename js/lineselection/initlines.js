let latestSelection = 1;

$("main span a").click(function (e) {
    if (e.ctrlKey) {
        return false;
    }
    if (e.shiftKey) {
        return false;
    }
})

window.onload = (event) => {
    $("span").removeClass("x-target");
    newSelections();
    if (window.location.hash != "") {
        latestSelection = simplify(window.location.hash.substring(2).split(",")).reverse()[0];
    }
};

window.onhashchange = function () {
    $("span").removeClass("x-target");
    newSelections();
}

function newSelections() {
    let targets = window.location.hash.substring(2).split(",");
    for (let i = 0; i < targets.length; i++) {
        // Is this substring a RANGE of values? If so...
        if (targets[i].includes("-")) {
            lower = parseInt(targets[i].split("-")[0]);
            upper = parseInt(targets[i].split("-")[1]);
            // ...highlight every value in that range.
            for (let j = lower; j <= upper; j++) {
                currentID = "p" + String(j);
                let currentTarget = document.getElementById(currentID);
                currentTarget.classList.add("x-target");
                if (i === 0) {
                    currentTarget.scrollIntoView({ behavior: "auto", block: "center" });
                }
            }
        } else {
            if (targets[i] != "") {
                currentID = "p" + targets[i];
                let currentTarget = document.getElementById(currentID);
                currentTarget.classList.add("x-target");
                if (i === 0) {
                    currentTarget.scrollIntoView({ behavior: "auto", block: "center" });
                }
            }
        }
    }
}

function newClassSelections() {
    // Experimental version of above function that targets classes instead of IDs (since, with various new version functions, multiple paragraphs on the same page can now share the same number.)
    let targets = window.location.hash.substring(2).split(",");
    for (let i = 0; i < targets.length; i++) {
        // Is this substring a RANGE of values? If so...
        if (targets[i].includes("-")) {
            lower = parseInt(targets[i].split("-")[0]);
            upper = parseInt(targets[i].split("-")[1]);
            // ...highlight every value in that range.
            for (let j = lower; j <= upper; j++) {
                currentID = "p" + String(j);
                let currentTarget = document.getElementById(currentID);
                currentTarget.classList.add("x-target");
                if (i === 0) {
                    currentTarget.scrollIntoView({ behavior: "auto", block: "center" });
                }
            }
        } else {
            if (targets[i] != "") {
                currentID = "p" + targets[i];
                let currentTarget = document.getElementById(currentID);
                currentTarget.classList.add("x-target");
                if (i === 0) {
                    currentTarget.scrollIntoView({ behavior: "auto", block: "center" });
                }
            }
        }
    }
}

$(".anchor").click(function (e) {
    // Weird behavior on DE-selection. Fix.
    if (e.ctrlKey && $(this).parent().hasClass("x-target")) {
        removeHash($(this).parent().attr("id"));
        $(this).parent().removeClass("x-target");
    } else if (e.ctrlKey && !($(this).parent().hasClass("x-target"))) {
        addHash($(this).parent().attr("id"));
        $(this).parent().addClass("x-target");
        latestSelection = $(this).parent().attr("id").substring(1);
    }

    /* SHIFT CLICK: find max value beneath selected value (1 if none targeted yet) and target evertyhing in between.
    Removed as it does not currently work.
     else if (e.shiftKey && !($(this).parent().hasClass("x-target"))) {
        let lowEnd = 0;
        let highEnd = 0;

        if (parseInt($(this).parent().attr("id").substring(1)) > latestSelection) {
            lowEnd = latestSelection;
            highEnd = parseInt($(this).parent().attr("id").substring(1));
        } else {
            highEnd = latestSelection;
            lowEnd = parseInt($(this).parent().attr("id").substring(1));
        }

        for (let i = lowEnd; i <= highEnd; i++) {
            addHash("p" + String(i));
            $("#p" + String(i)).addClass("x-target");
        }
    }
    */
});

function removeHash(id) {
    let currentHashes = simplify(window.location.href.split("#")[1].substring(1).split(","));
    console.log(currentHashes);
    let removedHash = parseInt(id.substring(1));
    console.log(removedHash);
    const indexRemoved = currentHashes.indexOf(removedHash);

    if (indexRemoved > -1) {
        currentHashes.splice(indexRemoved, 1);
    }

    if (currentHashes.length === 0) {
        window.location.href = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else {
        window.location.hash = "p" + condense(simplify(currentHashes)).join(",");
    }
}

function addHash(id) {
    if (window.location.hash == "") {
        window.location.hash = id;
    } else {
        let currentHashes = window.location.href.split("#")[1].substring(1).split(",");
        let addedHash = parseInt(id.substring(1));
        currentHashes.push(addedHash);
        currentHashes.sort(function (a, b) { return a - b });

        window.location.hash = "p" + condense(simplify(currentHashes)).join(",");
    }
}

function simplify(a) {
    // Convert any ranges in a to raw numbers.
    for (i = 0; i < a.length; i++) {
        if (typeof a[i] === "string" && a[i].includes("-")) {
            let lower = parseInt(a[i].split("-")[0]);
            let upper = parseInt(a[i].split("-")[1]);
            for (j = lower; j <= upper; j++) {
                a.push(j);
            }
            a.splice(i, 1);
        }
    }

    // Convert all to ints.
    a = a.map(function (x) {
        return parseInt(x, 10);
    });

    // Remove duplicates.
    if (a.length > 1) {
        a = a.filter((value, index) => a.indexOf(value) === index);
    }

    return a.sort(function (a, b) { return a - b });
}

// This function condenses any subarrays of sequential numbers into ranges of numbers.
// Ex.: [1, 2, 3, 6, 8, 10, 11, 12] becomes ["1-3", 6, 8, "10-12"]
function condense(a) {
    for (i = 0; i < a.length; i++) {
        let consecutives = [];
        consecutives.push(a[i]);
        let j = i + 1;
        while (j < a.length) {
            if (a[j] == (a[j - 1] + 1)) {
                consecutives.push(a[j]);
                j++;
            } else {
                break;
            }
        }
        if (consecutives.length >= 2) {
            let range = String(a[i]) + "-" + String(a[j - 1]);
            a.splice(i, j - i, range);
        }
    }
    return a.map(String);
}

function searchLines() {
    let url = new URL(window.location.href);

    if (url.searchParams.has("q")) {
        const search = decodeURI(url.searchParams.get("q"));

        let paragraphs = document.getElementById("oldhtml").getElementsByTagName("span");
        if (paragraphs.length > 0) {
            let results = [];
            for (i = 0; i < paragraphs.length; i++) {
                if (paragraphs[i].innerText.toLowerCase().includes(search.toLowerCase())) {
                    results.push(parseInt(paragraphs[i].getAttribute("id").substring(1)));
                }
            }

            let condensedResults = condense(results);
            url.searchParams.delete("q");
            window.location.href = url.href + "#p" + condensedResults.join(",");
        } else {
            url.searchParams.delete("q");
            window.location.href = url.href;
        }
    }
}

searchLines();