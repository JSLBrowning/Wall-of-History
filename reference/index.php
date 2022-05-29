<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" type="text/css" href="/css/reference.css">
    <title><?php
            if (count($_GET) == 1) {
                include("..//php/db_connect.php");

                // Create selection statement.
                // Currently needs work.
                $sql = "SELECT DISTINCT title FROM reference_titles WHERE `name` COLLATE UTF8_GENERAL_CI LIKE '%" . $_GET["id"] . "%'";

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
            <section class="story">
                <?php
                if (count($_GET) == 1) {
                    echo "<h3><a onclick='window.location.href=\"/reference/\"'>Reference</a></h3>";
                    include("..//php/db_connect.php");
                    $sql = "SELECT main FROM reference_content WHERE entry_id IN (SELECT DISTINCT entry_id FROM reference_metadata WHERE subject_id='" . $_GET['id']. "')";
                    $result = $mysqli->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo $row["main"];
                    }
                    $sql_appreances = "SELECT DISTINCT story_id FROM reference_appearances WHERE subject_id='" . $_GET['id'] . "'";
                    $result_appreances = $mysqli->query($sql_appreances);
                    echo "Appears in: ";
                    while ($row = $result_appreances->fetch_assoc()) {
                        echo "<button onclick='goTo(\"" . $row["story_id"] . "\")'>" . $row["story_id"] . "</button> ";
                    }
                } else {
                    echo "<h1>Reference</h1>";
                    include("..//php/db_connect.php");
                    // Create selection statement.
                    // $sql = "SELECT id, parent, fulltitle AS title, path FROM wall_of_history_contents WHERE childless=1 ORDER BY id ASC";
                    $sql = "SELECT DISTINCT title FROM reference_titles ORDER BY title ASC";
                    // Perfom selection.
                    $result = $mysqli->query($sql);
                    // $sql = "SELECT name AS fulltitle, content FROM wall_of_history_reference WHERE (`name` LIKE '%".$query."%') OR (`content` LIKE '%".$query."%')";
                    // Perfom selection.
                    // $result = $mysqli->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<ol id='sortable' style='list-style-type: none;'>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<li><a href='/reference/?id=" . $row["title"] . "'>" . $row['title'] . "</a></li>";
                        }
                        echo "</ol>";
                    } else {
                        echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
                    }
                    "<p><a href='/reference/?id=" . $row["title"] . "'>";
                }
                ?>
            </section>
        </article>
        <aside>
            <!-- Look into loading external modal content into a single modal on the fly: https://stackoverflow.com/questions/8988855/include-another-html-file-in-a-html-file -->
            <!-- MAIN NAVIGATION MENU MODAL -->
            <button id="navigationButton" onclick="toggleModal('navigationModal')">&#9776;</button>
            <div id="navigationModal" class="modal">
                <div class="modal-content modal-content-left">
                    <p><a onclick="jumpTo()" style="cursor: pointer;">Read</a></p>
                    <p><a href="/read/">Contents</a></p>
                    <p><a href="/search/">Search</a></p>
                    <p><a href="/about/">About</a></p>
                    <p><a href="https://blog.wallofhistory.com/">Blog</a></p>
                    <p><a href="https://www.maskofdestiny.com/news/tags/wall-of-history">News</a></p>
                    <p><a href="/contact/">Contact</a></p>
                </div>
            </div>
            <!-- SETTINGS MENU MODAL (WILL REDIRECT TO GLOBAL SETTINGS PAGE ON GLOBAL TABLE OF CONTENTS (NO ID PARAMETER)) -->
            <button id="settingsButton" onclick="window.location.href='/settings/';">&#9881;</button>
            <button id="paletteSwapButton" onclick="swapPalettes()">☀</button>
            <button id="paletteSwapButton" onclick="increaseFontSize()">↑</button>
            <button id="paletteSwapButton" onclick="decreaseFontSize()">↓</button>
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
    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="/js/main.js"></script>
    <script src="/js/palette.js"></script>
    <!-- Reader Drivers -->
    <script src="/js/lineselection/initlines.js"></script>
    <script src="/js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="/js/modal.js"></script>
</body>

</html>