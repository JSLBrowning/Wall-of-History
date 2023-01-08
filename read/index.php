<!DOCTYPE html>
<?php
include("../php/db_connect.php");
include("../php/populate.php");
include("../php/populateaside.php");
chooseColors();
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
            $sql = "SELECT tag FROM story_tags WHERE detailed_tag=\"" . $_GET["s"] . "\"";
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                $arr = explode(".", $row["tag"]);
                $id = $arr[0];
                $v = $arr[1];
                $lang = $arr[2];
            }
        } else {
            if (isset($_GET["id"])) {
                $id = $_GET["id"];

                if (is_numeric($id)) {
                    if ((int)$id < 99999) {
                        $sql = "SELECT id FROM story_metadata WHERE chronology=" . $id . " LIMIT 1";
                        // If no content, get most recent one closest that does?
                        $result = $mysqli->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $id = $row["id"];
                            echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://wallofhistory.com/read/?id=" . $id . "&lang=en&v=1'\" />\n";
                        }
                    }
                }
            } else {
                $id = "0";
            }

            if (isset($_GET["lang"])) {
                $lang = $_GET["lang"];
            } else {
                $lang = "eng";
            }

            if (isset($_GET["v"])) {
                $v = $_GET["v"];
            } else {
                $v = "1";
            }
        }
    } else {
        $id = "0";
        $lang = "eng";
        $v = "1";
    }
    populateHead($id, $lang, $v);
    ?>
    <link rel='stylesheet' type='text/css' href='/css/main.css'>
    <link rel='stylesheet' type='text/css' href='/css/modal.css'>
    <link rel='stylesheet' type='text/css' href='/css/cards.css'>
    <?php
    // Pass in stack cookie.
    populateCSS($id, $lang, $v);
    ?>
</head>

<body>
    <header>
        <?php
        loadHeader($id, $lang, $v);
        echo "<p id='downloadMarker' style='display:none'>" . $id . "</p>";
        ?>
        <a class="chip-wrapper" href="https://www.maskofdestiny.com/">
            <img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="/img/chips/mod.webp" width="64" height="64">
        </a>
    </header>
    <main>
        <article>
            <?php
            loadContent($id, $v, $lang);
            addChildren($id, $lang, $v);
            ?>
            </section>
            <div class="savefile" style="display:none;">
                <button type="savefilebutton" onclick="savePlace()">Save</button>
                <button type="savefilebutton" onclick="loadPlace()">Load</button>
            </div>
            <div class="nav" style="display:none">
                <button type="navbutton" onclick="goBack()" id="backbutton" style="display:none"><span class="leftarrow"></span></button>
                <button type="navbutton" onclick="generateSelectionModal()" id="disambiguationbutton" style="display:none">?</button>
                <button type="navbutton" onclick="goForward()" id="forwardbutton" style="display:none"><span class="rightarrow"></span></button>
            </div>
        </article>

        <aside>
            <button class="hideShow" onclick="hideShow(this)"><strong><span class="rightarrow"></span> </strong>Main Menu</button>
            <div class="asideMain">
                <form action="/search/">
                    <input type="text" required="required" placeholder="Search…" name="q">
                    <button type="submit">🔎︎</button>
                </form>
                <hr>
                <?php
                populateAside($id, $lang, $v);
                ?>
                <button class="small" onclick="window.location.href='/read/';">Contents</button>
                <button class="small" onclick="window.location.href='/reference/';">Reference</button>
                <button class="small" onclick="window.location.href='/settings/';">Settings</button>
                <hr>
                <button class="small" onclick="window.location.href='/about/';">About</button>
                <button class="small" onclick="window.location.href='https://blog.wallofhistory.com';">Blog</button>
                <button class="small" onclick="window.location.href='https://www.maskofdestiny.com/news/tags/wall-of-history';">News</button>
                <button class="small" onclick="window.location.href='/contact/';">Contact</button>
                <hr>
                <button class="small" onclick="increaseFontSize()">Inc. Font Size</button>
                <button class="small" onclick="decreaseFontSize()">Dec. Font Size</button>
                <button class="small" onclick="swapPalettes()">Swap Palette</button>
                <button class="small" onclick="matoranMode()">Matoran Mode</button>
            </div>
        </aside>
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