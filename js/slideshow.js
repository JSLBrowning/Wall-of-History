if ($("#slidelocation").length > 0) {
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("slideshow");
        if (n > x.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = x.length
        };
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        x[slideIndex - 1].style.display = "block";

        document.getElementById("slidelocation").textContent = slideIndex.toString() + " / " + x.length.toString();

        if (slideIndex == 1) {
            $("#slideshowback").hide();
            $("#slideshowforward").show();
            $("#slidelocationdiv").css("width", "50%");
            $("#slideshowforward").css("width", "50%");
        } else if (slideIndex == x.length) {
            $("#slideshowforward").hide();
            $("#slideshowback").show();
            $("#slideshowback").css("width", "50%");
            $("#slidelocationdiv").css("width", "50%");
        } else {
            $("#slideshowback").show();
            $("#slideshowforward").show();
            $("#slideshowback").css("width", "33.33%");
            $("#slidelocationdiv").css("width", "33.33%");
            $("#slideshowforward").css("width", "33.33%");
        }
    }
}

// Lightbox: https://code-boxx.com/image-zoom-css-javascript/