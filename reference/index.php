<!DOCTYPE html>
<?php
include("..//php/populate.php");
include("..//php/populatereference.php");

if (count($_GET)) {
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
    } else {
        $id = "0";
    }

    if (isset($_GET["lang"])) {
        $lang = $_GET["lang"];
    } else {
        $lang = "en";
    }

    if (isset($_GET["v"])) {
        $v = $_GET["v"];
    } else {
        $v = "1";
    }

    if (isset($_GET["pg"])) {
        $pg = $_GET["pg"];
    } else {
        $pg = "1";
    }
} else {
    $id = "0";
    $lang = "en";
    $v = "1";
    $pg = "1";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/reference/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Wall of History">
    <meta property="og:title" content="Reference | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp2.png" />
    <meta property="og:image:alt" content="Wall of History: The Complete BIONICLE Legend" />
    <meta name="theme-color" content="#938170">
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Reference | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp2.png" />
    <meta name="twitter:image:alt" content="Wall of History: The Complete BIONICLE Legend" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/cards.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/reference.css">
    <title><?php
            populateTitle($id);
            ?></title>
</head>

<body>
<header>
        <a class="chip-wrapper" href="https://www.maskofdestiny.com/"><img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="/img/chips/mod.webp" width="64" height="64"></a>
        <a href="/"><img src="../img/headers/Faber-Files-Bionicle-logo-Transparent.png"></a>
        <p>
            <a href='/read/'>Contents</a> |
            <a href="/reference/">Reference</a> |
            <a href="/about/">About</a> |
            <a href="https://blog.wallofhistory.com">Blog</a> |
            <a href="https://www.maskofdestiny.com/news/tags/wall-of-history">News</a> |
            <a href="/contact/">Contact</a>
        </p>
        <input type="text" placeholder="Search…">
    </header>
    <main>
        <article>
            <?php
            populateReferenceContent($id, $v, $lang, $pg);
            ?>
        </article>
    </main>

    <!-- modal -->
    <div id="myModal" class="modal">
        <!-- modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-data">
                <p>No information is available at this time.</p>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="../js/jquery/jquery-3.6.3.min.js"></script>
    <script src="../js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="../js/main.js"></script>
    <script src="../js/palette.js"></script>
    <!-- Reader Drivers -->
    <script src="../js/lineselection/initlines.js"></script>
    <script src="../js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="../js/modal.js"></script>
</body>

</html>