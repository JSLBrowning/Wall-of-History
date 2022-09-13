<!DOCTYPE html>
<?php
include("..//php/populate.php");
chooseColors();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/settings/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Wall of History">
    <meta property="og:title" content="Settings | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <meta name="theme-color" content="#938170">
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Settings | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/settings.css">
    <title>Settings | Wall of History</title>
</head>

<body>
    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <a class="chip-wrapper" href="https://www.maskofdestiny.com/">
            <img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="img/chips/mod.webp" width="64" height="64">
        </a>
    </header>
    <main>
        <aside>
            <button class="hideShow" onclick="hideShow(this)"><strong><span class='rightarrow'></span> </strong>Main Menu</button>
            <div>
                <form action="/search/">
                    <input type="text" required="required" placeholder="Search…" name="q">
                    <button type="submit">🔎︎</button>
                </form>
                <hr>
                <button class="small" onclick="window.location.href='/read/';">Contents</button>
                <button class="small" onclick="window.location.href='/reference/';">Reference</button>
                <button class="small" onclick="window.location.href='/settings/';">Settings</button>
                <hr>
                <button class="small" onclick="window.location.href='/about/';">About</button>
                <button class="small" onclick="window.location.href='https://blog.wallofhistory.com';">Blog</button>
                <button class="small" onclick="window.location.href='https://www.maskofdestiny.com/tags/wall-of-history';">News</button>
                <button class="small" onclick="window.location.href='/contact/';">Contact</button>
                <hr>
                <button class="small" onclick="increaseFontSize()">Increase Font Size</button>
                <button class="small" onclick="decreaseFontSize()">Decrease Font Size</button>
                <button class="small" onclick="swapPalettes()">Swap Color Palette</button>
                <button class="small" onclick="matoranMode()">Matoran Mode</button>
            </div>
        </aside>
        <article>
            <section class="story">
                <h1>Settings</h1>
                <div class="loadingWrapper">
                    <div class="loadingIconWrapper">
                        <div class="loadingIcon"></div>
                    </div>
                    <p class="loadingMessage">Loading…</p>
                </div>
            </section>
            <button id="submit">Submit</button>
        </article>
    </main>
    <!-- jQuery -->
    <script src="../js/jquery/jquery-3.6.0.min.js"></script>
    <script src="../js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="../js/main.js"></script>
    <script src="../js/palette.js"></script>
    <!-- Settings Drivers -->
    <script src="../js/settings.js"></script>
    <!-- Modal Drivers -->
    <script src="../js/modal.js"></script>
</body>

</html>