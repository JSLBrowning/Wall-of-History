<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/search/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Search | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Search | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/read.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>Search | Wall of History</title>
    <style>
        form {
            margin: 1em;
            padding: 0em;
            display: flex;
            justify-content: center;
        }

        form input {
            margin: 0px;
            margin-top: 12px;
            margin-bottom: 6px;
            border: none;
            height: 24px;
            width: 60%;
        }

        form button {
            margin: 0em;
            margin-top: 12px;
            margin-bottom: 6px;
            border: none;
            border-radius: 0px;
            background-color: #333435;
            height: 24px;
            width: 30%;
            font-size: 1em;
            text-shadow: 2px 2px 5px #000;
            box-shadow: none;
        }

        main hr {
            margin-left: 0em;
            margin-right: 0em;
        }

        main h3 {
            margin-top: 1em;
            text-align: left;
            font-size: medium;
            cursor: pointer;
        }

        main h3:hover {
            color: #cccccc;
            text-decoration: none;
        }

        main h3:active {
            color: #99999a;
            text-decoration: none;
        }

        main h3:visited {
            color: #fff;
            text-decoration: none;
        }
    </style>
    <title>Document</title>
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
    <main>
        <form action="/search/" style="margin: auto;">
            <input type="text" placeholder="Search…" name="q">
            <button type="submit">🔍</button>
        </form>
        <!-- RESULTS ORDER:
        Reference collections with exact name.
        Story pages with name in title.
        Story pages with name in content.
        https://developer.mozilla.org/en-US/docs/Web/HTML/Element/datalist
        -->
        <hr>
        <?php
        if (count($_GET) == 1) {
            include("..//php/db_connect.php");

            // SIGH
            error_reporting(0);

            $query = $_GET['q'];

            $sql = "SELECT name, content FROM wall_of_history_reference WHERE (`name` LIKE '%" . $query . "%') OR (`content` LIKE '%" . $query . "%')";

            // Perfom selection.
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<h3 onclick = \"window.location.href='/reference/?id=" . $row["name"] . "';\">" . $row['name'] . "</h3>";
                    echo "<p> Reference page for " . $row['name'] . ".</p>";
                }
            }

            $sql = "SELECT id, content_version, title, snippet, main FROM woh_content WHERE (`title` LIKE '%" . $query . "%') OR (`main` LIKE '%" . $query . "%')";

            // Perfom selection.
            $result = $mysqli->query($sql);

            // $sql = "SELECT name AS fulltitle, content FROM wall_of_history_reference WHERE (`name` LIKE '%".$query."%') OR (`content` LIKE '%".$query."%')";

            // Perfom selection.
            // $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $sql_chapter = "SELECT tag FROM woh_tags WHERE (id = '" . $row["id"] . "' AND tag = 'chapter')";

                    $result_chapter = $mysqli->query($sql_chapter);
                    $num_chap = mysqli_num_rows($result_chapter);
                    if ($num_chap == 0) {
                        echo "<h3 onclick = \"window.location.href='/read/?id=" . $row["id"] . "&q=" . $query . "';\">" . $row['title'] . "</h3>";
                        echo "<p>" . $row['snippet'] . "</p>";
                    } else {
                        $sql_title = "SELECT title FROM woh_content JOIN woh_web ON woh_web.parent_id = woh_content.id WHERE woh_web.child_id = '" . $row["id"] . "' AND woh_web.child_version = " . $row["content_version"];

                        $result_title = $mysqli->query($sql_title);
                        while ($row_title = $result_title->fetch_assoc()) {
                            echo "<h3 onclick = \"window.location.href='/read/?id=" . $row["id"] . "&q=" . $query . "';\">" . $row_title["title"] . ": " . $row['title'] . "</h3>";
                            echo "<p>" . $row['snippet'] . "</p>";
                        }
                    }
                }
            } else {
                echo "<p>Your search returned zero results.</p>";
            }
        } else {
            echo "<p>Please enter a search term.</p>";
        }
        ?>
    </main>
    <!-- jQuery -->
    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="/js/main.js"></script>
    <!-- Reader Drivers -->
    <script src="/js/lineselection/initlines.js"></script>
    <script src="/js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="/js/modal.js"></script>
    <!-- Autocomplete -->
    <script>
        // Fix this.
        availableTags = localStorage.getItem("referenceTerms").split(",");

        $("input").autocomplete({
            source: availableTags
        });
    </script>
</body>

</html>