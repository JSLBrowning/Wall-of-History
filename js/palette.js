function swapPalettes() {
    console.log("Swapping palettes…");
    const root = document.documentElement;
    root.classList.add('white');

    document.documentElement.classList.toggle('light');
    root.classList.toggle('black');
}

// https://academind.com/tutorials/adding-dark-mode/
// https://codepen.io/fiszer/pen/MVoPdL

/**
body, header, main bg: #161616
main border: #202020

box-shadow: #080808, #202020

font: #fff
a hover: #cccccc
a active: #99999a */