window.onhashchange = function() {
    newSelections();
}

function clearOldHashes() {
    return new Promise(resolve => {
        $("span").removeClass("x-target");
        resolve(true);
    });
}

async function newSelections() {
    let clear = await clearOldHashes();
    if (clear === true) {
        let targets = window.location.hash.substring(2).split(",");
        for (let i = 0; i < targets.length; i++) {
            currentID = "p" + targets[i];
            let currentTarget = document.getElementById(currentID);
            currentTarget.classList.add("x-target");
        }
    }
}

// https://stackoverflow.com/questions/298503/how-can-you-check-for-a-hash-in-a-url-using-javascript