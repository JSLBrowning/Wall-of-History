<!DOCTYPE html>
<?php

?>
<html lang="en">
<script>
    // If localStorage.colorPreference == light, set light class on html.
    if (localStorage.colorPreference == "light") {
        document.documentElement.classList.add('light');
    } else if (localStorage.colorPreference == "dark") {
        document.documentElement.classList.add('dark');
    } else {
        // Check browser for dark mode preference.
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
            localStorage.setItem("colorPreference", "dark");
            sessionStorage.setItem("TestItem", "TestValue");
            localStorage.setItem("TestItem", "TestValue");
        } else {
            document.documentElement.classList.add('light');
            localStorage.setItem('colorPreference', 'light');
        }
    }
</script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Wall of History">
    <meta property="og:title" content="Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp2.png" />
    <meta property="og:image:alt" content="Wall of History: The Complete BIONICLE Legend" />
    <meta name="theme-color" content="#938170">
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp2.png" />
    <meta name="twitter:image:alt" content="Wall of History: The Complete BIONICLE Legend" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="css/main_old.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Wall of History</title>
</head>

<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v8.0" nonce="sckJu8Ly"></script>

    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>

    <a class="chip-wrapper" href="https://www.maskofdestiny.com/">
        <img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="/img/chips/mod.webp" width="64" height="64">
    </a>

    <main>
        <article>
            <video poster="img/video2.webp" controls>
                <source src="/img/Wall%20of%20History%20Ad.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <script>
                // If no localStorage is empty, set above video to autoplay.
                if (window.localStorage.length == 0) {
                    document.getElementsByTagName("video")[0].autoplay = true;
                } else {
                    document.getElementsByTagName("video")[0].autoplay = false;
                }
            </script>
            <?php
            include('./php/db_connect.php');
            include("./php/populate.php");
            echo populateStatic("");
            ?>
            <hr>
            <p>Find Us</p>
            <div class="social">
                <a href="https://discord.com/invite/V7KUyye" width="24px" height="24px">
                    <img src="../img/index/Discord-Logo-White.png" width="24px" height="24px">
                </a>
                <a href="https://www.facebook.com/WallofHistory" width="24px" height="24px">
                    <img src="../img/index/f_logo_RGB-White_1024.png" width="24px" height="24px">
                </a>
                <a href="https://www.instagram.com/Wall_of_History/" width="24px" height="24px">
                    <img src="../img/index/white-instagram-logo-transparent-background.png" width="24px" height="24px">
                </a>
                <a href="https://www.reddit.com/r/WallofHistory/" width="24px" height="24px">
                    <img src="../img/index/reddit_share_silhouette_128.png" width="24px" height="24px">
                </a>
                <a href="https://twitter.com/Wall_of_History" width="24px" height="24px">
                    <img src="../img/index/Twitter_Social_Icon_Circle_White.png" width="24px" height="24px">
                </a>
                <a href="https://www.youtube.com/channel/UCNnu_YlSMehzaAZwWS6gkVg" width="24px" height="24px">
                    <img src="../img/index/yt_logo_mono_dark.png" width="24px" height="24px">
                </a>
            </div>
        </article>
        <aside>
            <button class="hideShow" onclick="hideShow(this)"><strong><span class='rightarrow'></span> </strong>Main Menu</button>
            <div class="asideContainer">
                <div class="asideMain">
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
                    <button class="small" onclick="window.location.href='https\://blog.wallofhistory.com';">Blog</button>
                    <button class="small" onclick="window.location.href='https\://www.maskofdestiny.com/news/tags/wall-of-history';">News</button>
                    <button class="small" onclick="window.location.href='/contact/';">Contact</button>
                    <hr>
                    <button class="small" onclick="increaseFontSize()">Inc. Font Size</button>
                    <button class="small" onclick="decreaseFontSize()">Dec. Font Size</button>
                    <button class="small" onclick="swapPalettes()">Swap Palette</button>
                    <button class="small" onclick="matoranMode()">Matoran Mode</button>
                </div>
            </div>
        </aside>
    </main>
    <!-- modal -->
    <div id="myModal" class="modal">
        <!-- modal content -->
        <div class="modal-content modal-content-center">
            <div id="modal-data">
                <p>No information is available at this time.</p>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="js/jquery/jquery-3.6.3.min.js"></script>
    <script src="js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="js/main.js"></script>
    <!-- <script src="js/palette.js"></script> -->
    <!-- Reader Drivers -->
    <script src="js/readingorders.js"></script>
    <!-- Modal Drivers -->
    <script src="js/modal.js"></script>
</body>

</html>