<!DOCTYPE html>
<?php
include("../php/db_connect.php");
include("../php/populate.php");
if (count($_GET)) {
    if (isset($_GET["s"])) {
        $identifiers = translateFromSemantic($_GET["s"]);
        $id = $identifiers["id"];
        $v = $identifiers["v"];
        if ($v == "") {
            $v = "1";
        }
        $lang = $identifiers["lang"];
    } else if (isset($_GET["id"])) {
        $id = $_GET["id"];

        if (isset($_GET["v"])) {
            $v = $_GET["v"];
        } else {
            $versions = checkForMultipleVersions($id);
            // If length of $versions is 1...
            if (count($versions) == 1) {
                $v = $versions[0];
            } else {
                // Create disambiguation page. Eventually.
                // echo "<title>Disambiguation | Wall of History</title>";
                $v = "1";
            }
        }

        if (isset($_GET["lang"])) {
            $lang = $_GET["lang"];
        } else {
            // Get browser language.
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
    } else if (isset($_GET["t"])) {
        $type = $_GET["t"];
    }
} else {
    $id = "0";
    $v = "1";
    $lang = "en";
}
?>

<head>
    <script src="/js/palette.js"></script>
    <meta charset='UTF-8'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <meta property='og:site_name' content='Wall of History'>
    <meta content='summary_large_image' name='twitter:card'/>
    <meta content='@Wall_of_History' name='twitter:site'/>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/fonts/fontawesome-free-6.3.0-web/css/all.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/cards.css">
    <?php
    if (isset($type) && $type == "main") {
        echo "<title>Contents by Type | Wall of History</title>";
    } else if (isset($type)) {
        echo "<title>" . pluralizeTypeTag($type) . " | Wall of History</title>";
    } else if (isset($id)) {
        populateCSS($id);
        populateHead($id, $v, $lang);
    }
    ?>
</head>

<body>
    <header>
        <a class="chip-wrapper" href="https://www.maskofdestiny.com/"><img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="/img/chips/mod.webp" width="64" height="64"></a>
        <a href="/">
            <?php
            if (isset($id)) {
                loadHeader($id);
            } else {
                echo '<img src="/img/headers/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE">';
            }
            ?>
        </a>
        <p>
            <?php
            if (isset($id)) {
                echo "<a href='/read/'>Contents</a> | ";
            }
            ?>
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
            $route = getRoute("d9669c6a-d648-11ed-beaa-00ff2a5c27e8");

            // $firstPage = getFirstPage($route);
            // print_r($firstPage);
            //$fullURL = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            //echo "<p>" . $fullURL . "</p>";
            // echo getMainContent($firstPage["content_id"], $firstPage["content_version"]);
            ?>
            <div class="article__content">
                <section class="title__box">
                    <section class="title__box__text">
                        <?php
                        // If id variable is set.
                        if (isset($id)) {
                            getTitleBoxText($id, $v);
                            $config = getJSONConfigVariables();
                        } else {
                            echo "<h1 style='text-align:center;'>" . pluralizeTypeTag($type) . "</h1>";
                        }
                        ?>
                    </section>
                </section>
                <section class="extra__content">
                    <?php
                        include("../php/populateextras.php");
                        if (isset($id) && isset($lang) && isset($v)) {
                            populateAside($id, $lang, $v);
                        }
                    ?>
                </section>
                <section class="main__content">
                    <?php
                    /*
                        include("../php/populate.php");
                        $route = getRoute("d9669c6a-d648-11ed-beaa-00ff2a5c27e8");
                        $first_page = findFirstPage($route);
                        // Echo the array.
                        print_r($first_page);
                        $neighbors = getNeighbors($route, $firstPage["content_id"], $firstPage["content_version"]);
                    */

                    if (isset($id)) {
                        if ($id == "0") {
                            addTableOfContents($id);
                        } else {
                            getMainContent($id, $v);
                            addTableOfContents($id, $v);
                        }
                    } else if (isset($type)) {
                        getTypeChildren($type);

                        function getEntriesOfType($type)
                        {
                            include($_SERVER['DOCUMENT_ROOT'] . "/php/db_connect.php");

                            // Get all ID.v.lang combos of the type in question.
                            $query = "SELECT content_id, content_version, content_language FROM shin_metadata WHERE content_id NOT IN (SELECT child_id FROM shin_web WHERE parent_id IN (SELECT content_id FROM shin_tags WHERE tag='$type')) AND content_id IN (SELECT content_id FROM shin_tags WHERE tag_type='type' AND tag='$type') ORDER BY chronology, release_date ASC";
                            // Need to join this with TITLE from shin_content so they can be ordered right. Also need to update release dates to be accurate.
                            $result = $mysqli->query($query);
                            $entries = array();
                            while ($row = $result->fetch_assoc()) {
                                $entries[] = $row;
                            }
                            return $entries;
                        }

                        $entriesOfType = getEntriesOfType($type);
                        echo "<div class='deck'>";
                        foreach ($entriesOfType as $entry) {
                            $id = $entry["content_id"];
                            $v = $entry["content_version"];
                            $l = $entry["content_language"];
                            $content_data = getContentData($id, $v, $l);

                            if (count($content_data) > 0) {
                                // Get image based on content_data.
                                echo "<a class='card medium__card' href='/read/?id=$id'>";
                                echo "<div class='card__text'>";
                                echo "<h3>" . $content_data[0]["content_title"] . "</h3>";
                                echo "<p>" . $content_data[0]["content_snippet"] . "</p>";
                                echo "</div>";
                                echo "</a>";
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </section>
                <div>
        </article>
        <nav>
            <div class="nav__column">
            </div>
        </nav>
    </main>
    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- Modal Content -->
        <div class="modal-content modal-content-center">
            <div id="modal-data"></div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="../js/jquery/jquery-3.6.3.min.js"></script>
    <script src="../js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Reading Order Drivers -->
    <script src="../js/readingorders.js"></script>
    <!-- Core Site Drivers -->
    <script src="../js/main.js"></script>
    <!-- Reader Drivers -->
    <script src="../js/read.js"></script>
    <script src="../js/lineselection/initlines.js"></script>
    <script src="../js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="../js/modal.js"></script>
    <!-- Ruffle (Flash Driver) -->
    <script>
        window.RufflePlayer = window.RufflePlayer || {};
        window.RufflePlayer.config = {
            "autoplay": "off",
            "contextMenu": true,
            "menu": true,
            "quality": "best"
        }
    </script>
    <script src="../js/ruffle/ruffle.js"></script>
    <script src="../js/flash.js"></script>
    <!-- Unused
    <script src="/js/compare/compare.js"></script>
    -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.122.0/examples/js/controls/OrbitControls.min.js"></script>
    <script src="/js/3d.js"></script>
</body>

</html>