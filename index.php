<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/" />
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
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!-- Put anything in read.css that's needed here into main.css. -->
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/test.css">
    <title>Wall of History</title>
</head>

<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v8.0" nonce="sckJu8Ly"></script>
    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>
    
    <main>
        <article>
            <video poster="img/Video Thumbnail.png" controls>
                <source src="https://wallofhistory.com/img/Wall%20of%20History%20Ad.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <!-- WHAT HAPPENED TO THE CODE THAT PICKED ONE OF THESE?!?! -->
            <?php
                include('/php/db_connect.php');
                include('/php/populate.php');
                echo populateStatic("");
            ?>
            <hr>
            <p style="text-align: center;">Find Us</p>
            <div class="social" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
                <!-- Replace w/ text-based buttons. -->
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
            <p style="text-align: center;">Share</p>
            <div class="social" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
                <!-- Replace w/ text-based buttons. -->
                <a href="https://www.facebook.com/sharer/?u=https%3A%2F%2Fwallofhistory.com%2F" width="24px" height="24px">
                    <img src="../img/index/f_logo_RGB-White_1024.png" width="24px" height="24px">
                </a>
                <a href="https://twitter.com/intent/tweet?text=I'm living the %23BIONICLE legend on @Wall_of_History, and you should be too! https://wallofhistory.com/" width="24px" height="24px">
                    <img src="../img/index/Twitter_Social_Icon_Circle_White.png" width="24px" height="24px">
                </a>
            </div>
        </article>
        <aside>
            <!-- Look into loading external modal content into a single modal on the fly: https://stackoverflow.com/questions/8988855/include-another-html-file-in-a-html-file -->
            <!-- MAIN NAVIGATION MENU MODAL -->
            <button id="navigationButton" onclick="toggleModal('navigationModal')">&#9776; Main Menu</button>
            <div id="navigationModal" class="modal">
                <div class="modal-content modal-content-left">
                    <span class="close" id="navigationClose" onclick="toggleModal('navigationModal')">&times;</span>
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
            <button onclick="window.location.href='/settings/';">&#9881; Settings</button>
            <hr>
            <button class="small" onclick="increaseFontSize()">Increase Font Size</button>
            <button class="small" onclick="decreaseFontSize()">Decrease Font Size</button>
            <button class="small" onclick="swapPalettes()">Swap Color Palette</button>
        </aside>
    </main>
    <!-- modal -->
    <div id="myModal" class="modal">
            <!-- modal content -->
            <div class="modal-content modal-content-center">
                <span class="close">&times;</span>
                <div id="modal-data">
                    <p>No information is available at this time.</p>
                </div>
            </div>
        </div>
    <!-- jQuery -->
    <script src="js/jquery/jquery-3.6.0.min.js"></script>
    <script src="js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="js/main.js"></script>
    <script src="js/palette.js"></script>
    <!-- Reader Drivers -->
    <script src="js/readingorders.js"></script>
    <!-- Modal Drivers -->
    <script src="js/modal.js"></script>
</body>

</html>