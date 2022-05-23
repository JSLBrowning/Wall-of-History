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
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/settings.css">
    <title>Settings | Wall of History</title>
</head>

<body>
    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>
    <main>
        <aside>
            <!-- Look into loading external modal content into a single modal on the fly: https://stackoverflow.com/questions/8988855/include-another-html-file-in-a-html-file -->
            <!-- MAIN NAVIGATION MENU MODAL -->
            <button id="navigationButton" onclick="toggleModal('navigationModal')">&#9776; Main Menu</button>
            <div id="navigationModal" class="modal">
                <div class="modal-content modal-content-left">
                    <p>
                        <?php
                        if (count($_GET)) {
                            echo "<a href=\"/read/\">Contents</a>";
                        } else {
                            echo "<a onclick=\"jumpTo()\" style=\"cursor: pointer;\">Read</a>";
                        }
                        ?>
                    </p>
                    <p><a href="/reference/">Reference</a></p>
                    <p><a href="/search/">Search</a></p>
                    <p><a href="/about/">About</a></p>
                    <p><a href="https://blog.wallofhistory.com/">Blog</a></p>
                    <p><a href="https://www.maskofdestiny.com/news/tags/wall-of-history">News</a></p>
                    <p><a href="/contact/">Contact</a></p>
                </div>
            </div>
            <hr>
            <button class="small" onclick="increaseFontSize()">Increase Font Size</button>
            <button class="small" onclick="decreaseFontSize()">Decrease Font Size</button>
            <button class="small" onclick="swapPalettes()">Swap Color Palette</button>
        </aside>
        <article>
            <section class="story">
                <h1>Settings</h1>
                <?php
                populateSettings();
                ?>
            </section>
            <button id="submit">Submit</button>
        </article>
    </main>
    <!-- jQuery -->
    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="/js/main.js"></script>
    <script src="/js/palette.js"></script>
    <!-- Settings Drivers -->
    <script src="/js/settings.js"></script>
    <!-- Modal Drivers -->
    <script src="/js/modal.js"></script>
</body>

</html>