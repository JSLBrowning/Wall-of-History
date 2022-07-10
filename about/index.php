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
    <meta property="og:url" content="https://wallofhistory.com/about/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="About | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="About | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/about.css">
    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <title>About | Wall of History</title>
</head>

<body>
    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>
    <main>
        <article>
            <img src="https://wallofhistory.com/img/social/Profile%20Picture.png" alt="Wall of History logo" title="Wall of History">
            <h1>About BIONICLE</h1>
            <p>“Since it began in 2001, the BIONICLE universe has grown from being a construction toyline with a story
                behind it to a modern mythology. Filled with amazing locations, interesting characters, mystery, drama,
                and action, it has inspired movies,
                novels, [and] web fiction.” — Greg Farshtey</p>
            <hr>
            <h1>About Wall of History</h1>
            <p>Wall of History is a web archive of the entire BIONICLE legend, created by a fan who wanted to provide
                others with an accessible way to experience it. The website is an eternal work in progress, so if you
                have any comments or suggestions,
                please contact us using one of the methods on the <a href="/contact/">contact</a> page.</p>
            <p>Wall of History is active on several social media platforms, and all of our accounts can be accessed
                through our <a href="https://linktr.ee/WallofHistory">Linktree</a>.</p>
            <h2>Our History</h2>
            <p>Inspired by multimedia web comics like <em>Homestuck</em>, the earliest version of Wall of History began
                development on October 1<sup>st</sup>, 2017, with a fan transcription of <a class="nonblock" href="/read/?id=3ab89c"><em>BIONICLE Chronicles #1: Tale of the Toa</em></a>.
                The earliest build of the website went live on February 23<span class="superscript">rd</span>, 2019, at
                about 7:00 AM UTC, and regular content updates continued until July of that same year.</p>
            <p>To learn more about our history, check out <a class="nonblock" href="https://www.maskofdestiny.com/news/the-history-of-wall-of-history/">“The History of Wall of
                    History”</a> on Mask of Destiny.</p>
            <p class="footer">BIONICLE and the BIONICLE logo are trademarks of the LEGO Group. © 2001 - 2010 The LEGO
                Group.</p>
        </article>
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
                <button class="small" onclick="window.location.href='blog.wallofhistory.com';">Blog</button>
                <button class="small" onclick="window.location.href='https://www.maskofdestiny.com/tags/wall-of-history';">News</button>
                <button class="small" onclick="window.location.href='/contact/';">Contact</button>
                <hr>
                <button class="small" onclick="increaseFontSize()">Increase Font Size</button>
                <button class="small" onclick="decreaseFontSize()">Decrease Font Size</button>
                <button class="small" onclick="swapPalettes()">Swap Color Palette</button>
                <button class="small" onclick="matoranMode()">Matoran Mode</button>
            </div>
        </aside>
        <!--
            <aside>
                <a class="twitter-timeline" data-height="500" data-theme="dark" href="https://twitter.com/Wall_of_History?ref_src=twsrc%5Etfw">Tweets by Wall_of_History</a>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                <hr>
                <iframe src="https://discord.com/widget?id=578079637356150785&theme=dark" width="350" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
            </aside>
        -->
    </main>
    <!-- jQuery -->
    <script src="../js/jquery/jquery-3.6.0.min.js"></script>
    <script src="../js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="../js/main.js"></script>
    <script src="../js/palette.js"></script>
    <!-- Reader Drivers -->
    <script src="../js/readingorders.js"></script>
    <!-- Modal Drivers -->
    <script src="../js/modal.js"></script>
</body>

</html>