function toggle(id) {
    var x = document.getElementById(id);
    if (x.style.display === "none") {
        $(x).slideDown(300);
    } else {
        $(x).slideUp(300);
    }
}