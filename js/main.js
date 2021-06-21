function initialize() {
    if (localStorage.getItem("version") != "1.0") {
        localStorage.setItem("version", "1.0");
    }

    if (localStorage.getItem("languagePreference") === null) {
        const lang = navigator.language;
        localStorage.setItem("languagePreference", lang.substring(0, 2));
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
};

initialize();

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