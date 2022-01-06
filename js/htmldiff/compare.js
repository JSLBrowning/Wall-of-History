function compare(newVersion) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const currentId = urlParams.get("id");
    const currentLang = urlParams.get("lang");
    console.log(newVersion);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("newhtml").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../php/getcomparedata.php?id=" + currentId + "&v2=" + newVersion + "&lang=" + currentLang, true);
    xmlhttp.send();

    // Run htmldiff on old and new <main>s, put result in diff <main>.
    let originalHTML = document.getElementById("oldhtml").innerHTML;
    let newHTML = document.getElementById("newhtml").innerHTML;
    let output = htmldiff(originalHTML, newHTML);
    document.getElementById("diff").innerHTML = output;

    // Show diff <main>.
    document.getElementById("diff").style.display = "block";

    // Dismiss settings modal.
    toggleModal("settingsModal");

    // Hide comparison dropdown and buttons, show dismiss button.
    // document.getElementById("comparison-dropdown").style.display = "none";
    // document.getElementById("comparison-buttons").style.display = "block";
}

function dismissComparison() {
    // Hide diff <main>.
    // Empty diff <main>.
    // Empty second <main>.
    // Hide dismiss button, show comparison dropdown and buttons.
}