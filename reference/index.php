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
    <meta property="og:url" content="https://wallofhistory.com/reference/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Reference | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Reference | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/read.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <title><?php
            if (count($_GET) == 1) {
                include("..//php/db_connect.php");

                // Create selection statement.
                // Currently needs work.
                $sql = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id = '" . $_GET["id"] . "') LIMIT 1";


                // Perfom selection.
                $result = $mysqli->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo str_replace("</em>", "", str_replace("<em>", "", array_shift($row)));
                    }
                }
            } else {
                echo "Reference";
            }
            ?> | Wall of History</title>
</head>

<body>
    <header>
        <img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>
    <main>
        <article>
            <?php
            if (count($_GET) == 1) {
                include("..//php/populatereference.php");
                populateReferenceContent($_GET["id"]);
            } else {
                include("..//php/populatereference.php");
                populateReferenceSubjects();
            }
            ?>
        </article>
        <aside>
            <button class="hideShow" onclick="hideShow(this)"><strong>⮞ </strong>Main Menu</button>
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
                    <button class="small" onclick="window.location.href='blog.wallofhistory.com';">Blog</button>
                    <button class="small" onclick="window.location.href='maskofdestiny.com/news/tags/wall-of-history';">News</button>
                    <button class="small" onclick="window.location.href='/contact/';">Contact</button>
                    <hr>
                    <button class="small" onclick="increaseFontSize()">Inc. Font Size</button>
                    <button class="small" onclick="decreaseFontSize()">Dec. Font Size</button>
                    <button class="small" onclick="swapPalettes()">Swap Palette</button>
                </div>
            </div>
        </aside>
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
    <script src="../js/jquery/jquery-3.6.0.min.js"></script>
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