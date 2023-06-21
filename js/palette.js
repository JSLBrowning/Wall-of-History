// Function to add relevant classes to the HTML element to change the color scheme and font size of the page.
function correct() {
    if (localStorage.getItem("fontSize") != null) {
        if (localStorage.getItem("colorScheme") === "light") {
            let colors = "light";
            let fontSize = localStorage.getItem("fontSize");
            switch (fontSize) {
                case "smallest":
                    document.documentElement.classList.add('smallest');
                    document.documentElement.classList.add(colors);
                    break;
                case "smaller":
                    document.documentElement.classList.add('smaller');
                    document.documentElement.classList.add(colors);
                    break;
                case "small":
                    document.documentElement.classList.add('small');
                    document.documentElement.classList.add(colors);
                    break;
                case "normal":
                    document.documentElement.classList.add(colors);
                    break;
                case "big":
                    document.documentElement.classList.add('big');
                    document.documentElement.classList.add(colors);
                    break;
                case "bigger":
                    document.documentElement.classList.add('bigger');
                    document.documentElement.classList.add(colors);
                    break;
                case "biggest":
                    document.documentElement.classList.add('biggest');
                    document.documentElement.classList.add(colors);
                    break;
            }
        } else {
            let fontSize = localStorage.getItem("fontSize");
            switch (fontSize) {
                case "smallest":
                    document.documentElement.classList.add('smallest');
                    break;
                case "smaller":
                    document.documentElement.classList.add('smaller');
                    break;
                case "small":
                    document.documentElement.classList.add('small');
                    break;
                case "normal":
                    console.log("Page ready.");
                    break;
                case "big":
                    document.documentElement.classList.add('big');
                    break;
                case "bigger":
                    document.documentElement.classList.add('bigger');
                    break;
                case "biggest":
                    document.documentElement.classList.add('biggest');
                    break;
            }
        }
    } else {
        if (localStorage.getItem("colorScheme") === "light") {
            document.documentElement.classList.toggle("light");
        }
    }

    if (localStorage.getItem("matoranMode") === "matoran") {
        document.documentElement.classList.toggle("matoran");
    }
}


// Do the above on document load.
document.addEventListener("DOMContentLoaded", correct);


// Function to swap between palette options.
function swapPalettes() {
    if (localStorage.getItem("colorScheme") === "light") {
        localStorage.setItem("colorScheme", "dark");
        document.cookie = "colorPreference=dark; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        document.documentElement.classList.toggle('light');
    } else {
        localStorage.setItem("colorScheme", "light");
        document.cookie = "colorPreference=light; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        document.documentElement.classList.toggle('light');
    }

    if (window.location.pathname.includes("about")) {
        location.reload();
    }
}


// Function to swap between Gill Sans and Matoran fonts.
function matoranMode() {
    if (localStorage.getItem("matoranMode") === "matoran") {
        localStorage.removeItem("matoranMode");
        document.cookie = "matoranMode=no; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        document.documentElement.classList.toggle('matoran');
    } else {
        localStorage.setItem("matoranMode", "matoran");
        document.cookie = "matoranMode=matoran; expires=Sat, 3 Nov 3021 12:00:00 UTC; path=/; SameSite=Lax;";
        document.documentElement.classList.toggle('matoran');
    }

    if (window.location.pathname.includes("about")) {
        location.reload();
    }
}


// Function to swap between font sizes.
function increaseFontSize() {
    let currentFontSize = localStorage.getItem("fontSize");

    switch (currentFontSize) {
        case "smallest":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("smaller");
            localStorage.setItem("fontSize", "smaller");
            break;
        case "smaller":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("small");
            localStorage.setItem("fontSize", "small");
            break;
        case "small":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            localStorage.setItem("fontSize", "normal");
            break;
        case "normal":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("big");
            localStorage.setItem("fontSize", "big");
            break;
        case "big":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("bigger");
            localStorage.setItem("fontSize", "bigger");
            break;
        case "bigger":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("biggest");
            localStorage.setItem("fontSize", "biggest");
            break;
        case "biggest":
            alert("Font is already at maximum size.");
            break;
    }
}


// Function to swap between font sizes.
function decreaseFontSize() {
    let currentFontSize = localStorage.getItem("fontSize");

    switch (currentFontSize) {
        case "smallest":
            alert("Font is already at minimum size.");
            break;
        case "smaller":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("smallest");
            localStorage.setItem("fontSize", "smallest");
            break;
        case "small":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("smaller");
            localStorage.setItem("fontSize", "smaller");
            break;
        case "normal":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("small");
            localStorage.setItem("fontSize", "small");
            break;
        case "big":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            localStorage.setItem("fontSize", "normal");
            break;
        case "bigger":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("big");
            localStorage.setItem("fontSize", "big");
            break;
        case "biggest":
            document.documentElement.classList.remove("smallest", "smaller", "small", "big", "bigger", "biggest");
            document.documentElement.classList.toggle("bigger");
            localStorage.setItem("fontSize", "bigger");
            break;
    }
}