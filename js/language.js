let full = localStorage.getItem("WallofHistoryLanguageList").split(",");
let available = document.getElementsByTagName("section");

if (available.length >= 1) {
    let a = []
    for (i = 0; i < available.length; i++) {
        a.push(available[i].lang);
    }

    let intersection = full.filter(x => a.includes(x));

    $("section:lang(" + intersection[0] + ")").css("display", "block");

    localStorage.setItem("WallofHistorySpoilerLevel", $("section").data("spoiler"));
}