<!DOCTYPE html>
<html lang="en">

<head>
    <script src="/js/palette.js"></script>
    <meta charset='UTF-8'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <?php
    include("..//php/populate.php");
    include("..//php/db_connect.php");
    if (count($_GET)) {
        if (isset($_GET["s"])) {
            $sql = "SELECT tag FROM woh_tags WHERE detailed_tag=\"" . $_GET["s"] . "\"";
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
                        $sql = "SELECT id FROM woh_metadata WHERE chronology=" . $id . " LIMIT 1";
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
                $lang = "en";
            }

            if (isset($_GET["v"])) {
                $v = $_GET["v"];
            } else {
                $v = "1";
            }
        }
    } else {
        $id = "0";
        $lang = "en";
        $v = "1";
    }
    populateHead($id, $lang, $v);
    ?>
    <link rel='stylesheet' type='text/css' href='/css/main.css'>
    <link rel='stylesheet' type='text/css' href='/css/contents.css'>
    <link rel='stylesheet' type='text/css' href='/css/modal.css'>
    <link rel='stylesheet' type='text/css' href='/css/test.css'>
    <?php
    // Pass in stack cookie.
    addCSS($id, $lang, $v);
    ?>
</head>

<body>
    <header>
        <?php
        loadHeader($id, $lang, $v);
        echo "<p id='downloadMarker' style='display:none'>" . $id . "</p>";
        ?>
    </header>
    <aside>
        <!-- Look into loading external modal content into a single modal on the fly: https://stackoverflow.com/questions/8988855/include-another-html-file-in-a-html-file -->
        <!-- MAIN NAVIGATION MENU MODAL -->
        <button id="navigationButton" onclick="toggleModal('navigationModal')">&#9776;</button>
        <div id="navigationModal" class="modal">
            <div class="modal-content modal-content-left">
                <span class="close" id="navigationClose" onclick="toggleModal('navigationModal')">&times;</span>
                <p>
                    <?php
                    if (count($_GET)) {
                        echo "<a href=\"/read/\">Contents</a>";
                    } else {
                        echo "<a onclick=\"jumpTo()\" style=\"cursor: pointer;\">Read</a>";
                    }
                    ?>
                </p>
                <p><a href="/reference/">Reference</a></p>
                <p><a href="/search/">Search</a></p>
                <p><a href="/about/">About</a></p>
                <p><a href="https://blog.wallofhistory.com/">Blog</a></p>
                <p><a href="https://www.maskofdestiny.com/news/tags/wall-of-history">News</a></p>
                <p><a href="/contact/">Contact</a></p>
            </div>
        </div>
        <!-- SETTINGS MENU MODAL (WILL REDIRECT TO GLOBAL SETTINGS PAGE ON GLOBAL TABLE OF CONTENTS (NO ID PARAMETER)) -->
        <button id="settingsButton" onclick="toggleModal('settingsModal')">&#9881;</button>
        <div id="settingsModal" class="modal">
            <div class="modal-content modal-content-right">
                <span class="close" id="settingsClose" onclick="toggleModal('settingsModal')">&times;</span>
                <?php
                include("..//php/populatesettingsmodal.php");
                populateSettingsModal($id, $v, $lang);
                ?>
            </div>
        </div>
        <button id="paletteSwapButton" onclick="swapPalettes()">☀</button>
        <button id="paletteSwapButton" onclick="increaseFontSize()">↑</button>
        <button id="paletteSwapButton" onclick="decreaseFontSize()">↓</button>
        <a id="downloadLink" target="_blank" style="display: none;"><button id="downloadButton">⭳</button></a>
    </aside>
    <div id="mains">
        <main>
            <?php
            loadContent($id, $lang, $v);
            ?>
        </main>
        <?php
        addChildren($id, $lang, $v);
        ?>
    </div>
    <div class="savefile" style="display:none;">
        <button type="savefilebutton" onclick="savePlace()">Save Place</button>
        <button type="savefilebutton" onclick="loadPlace()">Load Place</button>
    </div>
    <div class="nav" style="display:none">
        <button type="navbutton" onclick="goBack()" id="backbutton" style="display:none">←</button>
        <button type="navbutton" onclick="goForward()" id="forwardbutton" style="display:none">→</button>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- Modal Content -->
        <div class="modal-content modal-content-center">
            <span class="close">&times;</span>
            <div id="modal-data"></div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="/js/main.js"></script>
    <!-- Reader Drivers -->
    <script src="/js/read.js"></script>
    <script src="/js/readingorders.js"></script>
    <script src="/js/lineselection/initlines.js"></script>
    <script src="/js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="/js/populatemodals.js"></script>
    <script src="/js/modal.js"></script>
    <!-- Ruffle (Flash Driver) -->
    <script src="/js/ruffle/ruffle.js"></script>
    <!-- Unused
    <script src="/js/compare/compare.js"></script>
    -->
</body>

</html>