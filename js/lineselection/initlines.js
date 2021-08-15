$("main span a").click(function(e) {
    if (e.ctrlKey) {
        return false;
    }
})

window.onload = (event) => {
    $("span").removeClass("x-target");
    newSelections();
};

window.onhashchange = function() {
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
            }
        } else {
            if (targets[i] != "") {
                currentID = "p" + targets[i];
                let currentTarget = document.getElementById(currentID);
                currentTarget.classList.add("x-target");
            }
        }
    }
}

$(".anchor").click(function(e) {
    if (e.ctrlKey && $(this).parent().hasClass("x-target")) {
        removeHash($(this).parent().attr("id"));
        $(this).parent().removeClass("x-target");
    } else if (e.ctrlKey && !($(this).parent().hasClass("x-target"))) {
        addHash($(this).parent().attr("id"));
        $(this).parent().addClass("x-target");
    }
});

function removeHash(id) {
    let currentHashes = window.location.href.split("#")[1].substring(1).split(",");
    let removedHash = id.substring(1);
    const indexRemoved = currentHashes.indexOf(removedHash);

    if (indexRemoved > -1) {
        currentHashes.splice(indexRemoved, 1);
    }

    if (currentHashes.length === 0) {
        window.location.href = window.location.href.substr(0, window.location.href.indexOf('#'));
    } else {
        window.location.hash = "p" + currentHashes.join(",");
    }
}

function addHash(id) {
    if (window.location.hash == "") {
        window.location.hash = id;
    } else {
        let currentHashes = window.location.href.split("#")[1].substring(1).split(",");
        let addedHash = parseInt(id.substring(1));
        currentHashes.push(addedHash);
        currentHashes.sort(function(a, b) { return a - b });

        window.location.hash = "p" + currentHashes.join(",");
    }
}


let testArray = [1, 2, 3, 6, 8, 10, 11, 12, 15, 18];

for (i = 0; i < testArray.length; i++) {
    let consecutives = [];
    consecutives.push(testArray[i]);
    let j = i + 1;
    while (j < testArray.length) {
        if (testArray[j] == (testArray[j-1] + 1)) {
            consecutives.push(testArray[j]);
            j++;
        } else {
            break;
        }
    }
    if (consecutives.length > 2) {
        let range = String(testArray[i]) + "-" + String(testArray[j-1]);
        console.log(range);
    }
}