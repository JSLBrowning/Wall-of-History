/******************************
 * NEW MEDIA PLAYER FUNCTIONS *
 ******************************/


function getPosition() {
    let slideShowPosition = document.getElementsByClassName("slidelocation")[0];
    let position = slideShowPosition.textContent.split(" / ");
    return position;
}


function setPosition(trigger) {
    console.log('1');
    let slideShow = trigger.parentElement.parentElement;
    let slides = slideShow.getElementsByClassName("mediaplayercontents")[0];
    slideShow.getElementsByClassName("slidelocation")[0].textContent = "1 / " + slides.length.toString();
    trigger.parentElement.remove();
}


function backNav(button) {
    // If zoom element has at least one image child, do nothing.
    if (document.getElementsByClassName("zoom").length > 0) {
        console.log("Zoom open. Cannot flip page.");
    } else if (getPosition()[0] != 1) {
        slideshowNav(button, -1);
    }
}


function forwardNav(button) {
    let positions = getPosition();
    if (document.getElementsByClassName("zoom").length > 0) {
        console.log("Zoom open. Cannot flip page.");
    } else if (positions[0] != positions[1]) {
        slideshowNav(button, 1);
    }
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


window.addEventListener("keydown", function (event) {
    if (event.defaultPrevented) {
        return;
    }

    switch (event.code) {
        case "ArrowLeft":
            // Videos.
            rewind()
            // Comics.
            let backButton = document.getElementsByClassName("mediaplayerbutton")[0];
            backNav(backButton);
            break;
        case "ArrowRight":
            // Videos.
            fastForward();
            // Comics.
            let forwardButton = document.getElementsByClassName("mediaplayerbutton")[1];
            forwardNav(forwardButton);
            break;
    }

    // If left or right arrow keys are pressed, prevent default behaviour.
    if (event.code == "ArrowLeft" || event.code == "ArrowRight") {
        event.preventDefault();
    }
}, true);


// Function to rewind all video elements by 10 seconds.
function rewind() {
    $("video").each(function () {
        $(this).get(0).currentTime -= 5;
    });
}

// Function to fast forward all video elements by 10 seconds.
function fastForward() {
    $("video").each(function () {
        $(this).get(0).currentTime += 5;
    });
}
