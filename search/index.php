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
    <link rel="stylesheet" type="text/css" href="/css/search.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>Search | Wall of History</title>
    <style>
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
    <main>
        <article>
            <section class="story">
                <!-- RESULTS ORDER:
                Reference collections with exact name.
                Story pages with name in title.
                Story pages with name in content.
                https://developer.mozilla.org/en-US/docs/Web/HTML/Element/datalist
                -->
                <h1>Search Results for <?php
                echo $_GET['q'];
                ?></h1>
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
            </section>
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
                    <button class="small" onclick="window.location.href='blog.wallofhistory.com';">Blog</button>
                    <button class="small" onclick="window.location.href='https://www.maskofdestiny.com/tags/wall-of-history';">News</button>
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
    <!-- jQuery -->
    <script src="../js/jquery/jquery-3.6.0.min.js"></script>
    <script src="../js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="../js/main.js"></script>
    <!-- Reader Drivers -->
    <script src="../js/lineselection/initlines.js"></script>
    <script src="../js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="../js/modal.js"></script>
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