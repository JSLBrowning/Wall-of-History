<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/settings/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/read.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <title>Settings | Wall of History</title>
</head>

<body>
    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>
    <aside>
        <!-- Look into loading external modal content into a single modal on the fly: https://stackoverflow.com/questions/8988855/include-another-html-file-in-a-html-file -->
        <!-- MAIN NAVIGATION MENU MODAL -->
        <button id="navigationButton" onclick="toggleModal('navigationModal')">&#9776;</button>
        <div id="navigationModal" class="modal">
            <div class="modal-content modal-content-left">
                <span class="close" id="navigationClose" onclick="toggleModal('navigationModal')">&times;</span>
                <p><a onclick="jumpTo()" style="cursor: pointer;">Read</a></p>
                <p><a href="/read/">Contents</a></p>
                <p><a href="/reference/">Reference</a></p>
                <p><a href="/search/">Search</a></p>
                <p><a href="/about/">About</a></p>
                <p><a href="https://blog.wallofhistory.com/">Blog</a></p>
                <p><a href="https://www.maskofdestiny.com/news/tags/wall-of-history">News</a></p>
                <p><a href="/contact/">Contact</a></p>
            </div>
        </div>
        <button id="paletteSwapButton" onclick="swapPalettes()">☀</button>
        <button id="paletteSwapButton" onclick="increaseFontSize()">↑</button>
        <button id="paletteSwapButton" onclick="decreaseFontSize()">↓</button>
    </aside>
    <main>
        <h1>Settings</h1>
        <?php
        include("..//php/populate.php");
        populateSettings();
        ?>
        <button id="submit">Submit</button>
    </main>
    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/modal.js"></script>
    <!-- Fix modal error on settings page, possibly others. -->
    <script src="/js/settings.js"></script>
    <script src="/js/palette.js"></script>
</body>

</html>