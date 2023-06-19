<!DOCTYPE html>
<?php
include("../php/db_connect.php");
include("../php/populate.php");
?>

<head>
    <script src="/js/palette.js"></script>
    <meta charset='UTF-8'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <?php
    if (count($_GET)) {
        if (isset($_GET["s"])) {
            $identifiers = translateSemantic($_GET["s"]);
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
                $v = "1";
            }

            if (isset($_GET["lang"])) {
                $lang = $_GET["lang"];
            } else {
                $lang = "eng";
            }
        } else if (isset($_GET["t"])) {
            $type = $_GET["t"];
            if ($type == "main") {
                echo "<title>Contents by Type | Wall of History</title>";
            } else {
                echo "<title>" . pluralizeTypeTag($type) . " | Wall of History</title>";
            }
        }
    } else {
        $id = "0";
        $v = "1";
        $lang = "eng";
    }
    //populateHead($id, $lang, $v);
    ?>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/fonts/fontawesome-free-6.3.0-web/css/all.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/cards.css">
    <?php
    if (isset($id)) {
        populateCSS($id, $lang, $v);
    }
    ?>
</head>

<body>
    <header>
        <?php
        //loadHeader($id, $lang, $v);
        //echo "<p id='downloadMarker' style='display:none'>" . $id . "</p>";
        ?>
        <a class="chip-wrapper" href="https://www.maskofdestiny.com/"><img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="/img/chips/mod.webp" width="64" height="64"></a>
        <a href="/"><img src="../img/headers/Faber-Files-Bionicle-logo-Transparent.png"></a>
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
                            getJSONConfigVariables();
                        } else {
                            echo "<h1>Wall of History</h1>";
                        }
                        ?>
                    </section>
                </section>
                <section class="extra__content">
                    <form>
                        <select name="version__select">
                            <option value="0">Standard Editon</option>
                            <option value="1">Limited Collector’s Edition</option>
                        </select>
                        <select name="language__select">
                            <option value="en">English</option>
                            <option value="fr">French</option>
                            <option value="de">German</option>
                        </select>
                    </form>
                    <hr>
                    <!-- https://www.google.com/books/edition/Tale_of_the_Toa/-tjuYkYbDYAC?hl=en&kptab=overview -->
                    <span class="detail">
                        <p>Released:</p>
                        <p>June 1st, 2001</p>
                    </span>
                    <span class="detail">
                        <p>ISBN-13:</p>
                        <p>978-0439501163</p>
                    </span>
                    <hr>
                    <div class="extra__areas">
                        <a href="a" class="anchor__button"><i class="fa-solid fa-file-pdf fa-lg"></i> PDF</a>
                        <a href="a" class="anchor__button"><i class="fa-solid fa-file-word fa-lg"></i> DOCX</a>
                        <a href="a" class="anchor__button"><i class="fa-solid fa-tablet-screen-button fa-lg"></i> EPUB</a>
                        <a href="a" class="anchor__button"><i class="fa-solid fa-file-zipper fa-lg"></i> ZIP</a>
                    </div>
                    <hr>
                    <div class="extra__areas">
                        <a href="a" class="anchor__button"><i class="fa-solid fa-film"></i></i></i> Teaser</a>
                        <a href="a" class="anchor__button"><i class="fa-solid fa-film"></i></i></i> Trailer</a>
                        <a href="a" class="anchor__button"><i class="fa-solid fa-film"></i></i></i> TV Spot</a>
                    </div>
                    <hr>
                    <div class="extra__areas">
                        <a class="anchor__button" href="https://a.co/d/7J4JL1u"><i class="fa-brands fa-amazon"></i>
                            Amazon</a>
                    </div>
                </section>
                <section class="main__content">
                    <?php
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
                            $query = "SELECT content_id, content_version, content_language FROM shin_tags WHERE tag_type='type' AND tag='$type'";
                            $query = "SELECT content_id, content_version, content_language FROM shin_metadata WHERE content_id NOT IN (SELECT child_id FROM shin_web WHERE parent_id IN (SELECT content_id FROM shin_tags WHERE tag='$type')) AND content_id IN (SELECT content_id FROM shin_tags WHERE tag_type='type' AND tag='$type') ORDER BY chronology ASC";
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

                    // $neighbors = getNeighbors($route, $firstPage["content_id"], $firstPage["content_version"]);
                    // echo "<p>";
                    // print_r($neighbors);
                    // echo "</p>";
                    ?>
                </section>
                <div>
        </article>
        <nav>
            <div class="nav__column">
                <?php
                if (isset($id)) {
                    $neighbors = getNeighbors($route, $id, $v);
                    echo "<a class='card medium__card' onclick=\"window.location.search='id=" . $neighbors["next"]["content_id"] . "'\"><h2>Forward -></h2></a>";
                }
                ?>
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