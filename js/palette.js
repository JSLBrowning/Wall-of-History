function swapPalettes() {
    console.log("Swapping palettes…");
    const root = document.documentElement;
    root.classList.add('white');

    document.documentElement.classList.toggle('light');
    root.classList.toggle('black');
}

// https://academind.com/tutorials/adding-dark-mode/
// https://codepen.io/fiszer/pen/MVoPdL