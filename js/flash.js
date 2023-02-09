
// These can be called from a Flash file using the following:
// getURL("javascript: incrementProgress();","");


function defineProgress(points) {
    sessionStorage.setItem("progressPoints", points);
    sessionStorage.setItem("currentProgress", 0);
}


function updateProgressBar(newProgress) {
    let progressElement = document.getElementById("progressMNOG");
    let currentProgress = progressElement.value;
    let updateAnimation = setInterval(incrementProgress, 10);

    function incrementProgress() {
        if (currentProgress >= newProgress) {
            clearInterval(updateAnimation);
        } else {
            currentProgress++;
            progressElement.value = currentProgress;
        }
    }
}



function incrementProgress(flag = 0) {
    // Console log flag.
    console.log(flag);
    console.log("Flag functionality coming soon.");

    // If, somehow, currentProgress is not defined, set it to 1.
    if (sessionStorage.getItem("currentProgress") == null) {
        sessionStorage.setItem("currentProgress", 1);
    }

    // Get previous progress and increment it by 1.
    let currentProgress = parseInt(sessionStorage.getItem("currentProgress")) + 1
    sessionStorage.setItem("currentProgress", currentProgress);

    // Update progress bar with new progress.
    updateProgressBar(100 * currentProgress / sessionStorage.getItem("progressPoints"));
}


function resetProgress() {
    sessionStorage.setItem("currentProgress", 0);
    let progressElement = document.getElementById("progressMNOG");
    let updateAnimation = setInterval(decrementProgress, 10);

    function decrementProgress() {
        if (progressElement.value <= 0) {
            clearInterval(updateAnimation);
        } else {
            progressElement.value--;
        }
    }
}
