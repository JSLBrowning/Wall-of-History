/******************************
 * NEW MEDIA PLAYER FUNCTIONS *
 ******************************/


function setPosition(trigger) {
    console.log('1');
    let slideShow = trigger.parentElement.parentElement;
    let slides = slideShow.getElementsByClassName("mediaplayercontents")[0];
    slideShow.getElementsByClassName("slidelocation")[0].textContent = "1 / " + slides.length.toString();
    trigger.parentElement.remove();
}


function backNav(button) {
    direction = -1;
    slideshowNav(button, direction);
}


function forwardNav(button) {
    direction = 1;
    slideshowNav(button, direction);
}


function slideshowNav(button, direction) {
    let slideshow = button.parentElement.parentElement;

    // Improve this.
    $("video").each(function () {
        $(this).get(0).pause();
    });

    let slides = slideshow.getElementsByClassName("mediaplayercontents")[0];
    for (let i = 0; i < slides.children.length; i++) {
        styles = getComputedStyle(slides.children[i]);
        if (styles.display == "block") {
            slides.children[i].style.display = "none";
            let newCurrent = slides.children[i + direction];
            newCurrent.style.display = "block";

            let locationText = slideshow.getElementsByClassName("slidelocation")[0];
            let location = i + direction + 1;
            locationText.textContent = location.toString() + " / " + slides.children.length.toString();

            let forwardButton = button.parentElement.lastElementChild;
            let backButton = button.parentElement.firstElementChild;

            if ($(newCurrent).index() == slides.children.length - 1) {
                backButton.style.display = "block";
                forwardButton.style.display = "none";
            } else if ($(newCurrent).index() == 0) {
                backButton.style.display = "none";
                forwardButton.style.display = "block";
            } else {
                forwardButton.style.display = "block";
                backButton.style.display = "block";
            }

            break;
        }
    }
}


/*
window.addEventListener("keydown", function (event) {
    if (event.defaultPrevented) {
        return;
    }

    switch (event.code) {
        case "ArrowLeft":
            console.log("left");
            if (slideIndex != 1) {
                plusDivs(-1);

                if (document.getElementsByClassName("zoom") != null) {
                    document.getElementsByClassName("zoom")[0].getElementsByTagName("img")[0].src = document.getElementsByClassName("slideshow")[slideIndex - 1].src;
                }
            }
            break;
        case "ArrowRight":
            console.log("right");
            if (slideIndex != x.length) {
                plusDivs(+1);

                if (document.getElementsByClassName("zoom") != null) {
                    document.getElementsByClassName("zoom")[0].getElementsByTagName("img")[0].src = document.getElementsByClassName("slideshow")[slideIndex + 1].src;
                }
            }
            break;
    }

    event.preventDefault();
}, true);
*/