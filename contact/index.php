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
            <img src="https://wallofhistory.com/img/Profile%20Picture.png" alt="Wall of History logo" title="Wall of History">
            <h1>Contact</h1>
            <p>If you would like to contact Wall of Histor,y, please send us an <a href="mailto:admin@wallofhistory.com">email</a>, or message our official <a class="nonblock" href="https://www.facebook.com/WallofHistory/">Facebook</a>, <a class="nonblock" href="https://www.instagram.com/wall_of_history/">Instagram</a>,
                or <a class="nonblock" href="https://twitter.com/Wall_of_History">Twitter</a> accounts.</p>
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
                <button class="small" onclick="window.location.href='maskofdestiny.com/news/tags/wall-of-history';">News</button>
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