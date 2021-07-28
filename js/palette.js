function correct() {
    if (localStorage.getItem("colorScheme") === "light") {
        document.documentElement.classList.toggle('light');
        document.getElementById("paletteSwapButton").innerHTML = "☽";
        try {
            $(".twitter-timeline").attr("data-theme", "light");
        } catch (error) {
            console.log("No Twitter timeline is embedded on this page.");
        }
    }

    if (localStorage.getItem("fontSize") != null) {
        let fontSize = localStorage.getItem("fontSize");
        switch (fontSize) {
            case "smallest":
                document.documentElement.classList.toggle('smallest');
                break;
            case "smaller":
                document.documentElement.classList.toggle('smaller');
                break;
            case "small":
                document.documentElement.classList.toggle('small');
                break;
            case "big":
                document.documentElement.classList.toggle('big');
                break;
            case "bigger":
                document.documentElement.classList.toggle('bigger');
                break;
            case "biggest":
                document.documentElement.classList.toggle('biggest');
                break;
        }
    } else {
        localStorage.setItem("fontSize", "normal");
    }
}

correct();

function swapPalettes() {
    if (localStorage.getItem("colorScheme") === "light") {
        localStorage.setItem("colorScheme", "dark");
        document.documentElement.classList.toggle('light');
        document.getElementById("paletteSwapButton").innerHTML = "☀";
    } else {
        localStorage.setItem("colorScheme", "light");
        document.documentElement.classList.toggle('light');
        document.getElementById("paletteSwapButton").innerHTML = "☽";
    }

    if (window.location.pathname.includes("about")) {
        location.reload();
    }
}

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

/**
body, header, main bg: #161616
main border: #202020

box-shadow: #080808, #202020

font: #fff
a hover: #cccccc
a active: #99999a */