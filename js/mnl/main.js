function getArray() {
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

async function findSelf() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArray();

    return siblings.indexOf(id);
}

async function showButtons() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArray();
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

showButtons();

function savePlace() {
    localStorage.setItem("MythsandLegacySavePlace", window.location);
    alert("Your place was saved successfully.");
}

function jumpTo() {
    if (localStorage.getItem("MythsandLegacySavePlace") === null) {
        window.location.pathname = "/read";
    } else {
        window.location.href = localStorage.getItem("MythsandLegacySavePlace");
    }
}

function loadPlace() {
    if (localStorage.getItem("MythsandLegacySavePlace") === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("MythsandLegacySavePlace");
    }
}

async function goBack() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArray();
    siblings = siblings.split(",");
    let position = siblings.indexOf(id);
    window.location.href = "/read/?id=" + siblings[position-1];
}

async function goForward() {
    const urlParams = new URLSearchParams(window.location.search);
    let id = urlParams.get("id");
    let siblings = await getArray();
    siblings = siblings.split(",");
    let position = siblings.indexOf(id);
    window.location.href = "/read/?id=" + siblings[position+1];
}