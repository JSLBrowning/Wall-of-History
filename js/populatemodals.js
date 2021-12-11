function populateModalLinks() {
    // Fetch items...
    /* var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("referenceTerms", this.responseText);
        }
    };
    xmlhttp.open("GET", "../php/getreferenceterms.php", true);
    xmlhttp.send(); */
    // WHY NO WORK?!?!

    // ...and update modal links.
    let referenceItems = localStorage.getItem("referenceTerms").split(",");

    // Next thing. Add a regex for spaces and punctuation to fix the Makuta/Maku error on eba6c8.
    for (i = 0; i < referenceItems.length; i++) {
        $("p:contains('" + referenceItems[i] + " ')").html(function(_, html) {
            regex = new RegExp(referenceItems[i] + " ", "gi");
            return html.replace(regex, '<a data-reference="' + referenceItems[i] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[i] + '</a> ');
        });

        $("p:contains('" + referenceItems[i] + ",')").html(function(_, html) {
            regex = new RegExp(referenceItems[i] + ",", "gi");
            return html.replace(regex, '<a data-reference="' + referenceItems[i] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[i] + '</a>,');
        });

        $("p:contains('" + referenceItems[i] + ".')").html(function(_, html) {
            regex = new RegExp(referenceItems[i] + ".", "gi");
            return html.replace(regex, '<a data-reference="' + referenceItems[i] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[i] + '</a>.');
        });

        $("p:contains('" + referenceItems[i] + "…')").html(function(_, html) {
            regex = new RegExp(referenceItems[i] + "…", "gi");
            return html.replace(regex, '<a data-reference="' + referenceItems[i] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[i] + '</a>…');
        });

        $("p:contains('" + referenceItems[i] + "?')").html(function(_, html) {
            regex = new RegExp(referenceItems[i] + "?", "gi");
            return html.replace(regex, '<a data-reference="' + referenceItems[i] + '" onclick="getModalContent(this)" style="cursor: pointer;">' + referenceItems[i] + '</a>?');
        });

        $(".contentsText p").find("a").contents().unwrap();
    }
}

populateModalLinks();