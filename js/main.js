function initialize() {
    if (localStorage.getItem("version") != "1.0") {
        localStorage.setItem("version", "1.0");
    }

    if (localStorage.getItem("languagePreference") === null) {
        const lang = navigator.language;
        localStorage.setItem("languagePreference", lang.substring(0, 2));
    }

    if (localStorage.getItem("colorScheme") === null) {
        localStorage.setItem("colorScheme", "dark");
    }

    if (localStorage.getItem("readingOrder") === null || localStorage.getItem("version") === null) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("readingOrder", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initread.php", true);
        xmlhttp.send();
    }
};

initialize();