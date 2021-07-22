function check() {
    if (sessionStorage.getItem("activeReadingOrder") === null && Object.keys(localStorage).filter(name => name.includes('readingOrder')).length > 1) {
        generateSelectionModal();
    } else if (sessionStorage.getItem("activeReadingOrder") === null) {
        sessionStorage.setItem("activeReadingOrder", "0");
    }
}

check();

// If not on reader... empty sessionStorage.