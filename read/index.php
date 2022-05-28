<!DOCTYPE html>
<?php
    include("..//php/populate.php");
    chooseColors();
?>

<head>
    <script src="/js/palette.js"></script>
    <meta charset='UTF-8'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <?php
    include("..//php/db_connect.php");
    include("..//php/populateaside.php");
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
    </header>
    <main>
        <article>
            <?php
            loadContent($id, $v, $lang);
            ?>
            <section class="structure">
                <?php
                addChildren($id, $lang, $v);
                ?>
            </section>
            <div class="savefile" style="display:none;">
                <button type="savefilebutton" onclick="savePlace()">Save Place</button>
                <button type="savefilebutton" onclick="loadPlace()">Load Place</button>
            </div>
            <div class="nav" style="display:none">
                <button type="navbutton" onclick="goBack()" id="backbutton" style="display:none">⮜</button>
                <button type="navbutton" onclick="generateSelectionModal()" id="disambiguationbutton" style="display:none">?</button>
                <button type="navbutton" onclick="goForward()" id="forwardbutton" style="display:none">⮞</button>
            </div>
        </article>

        <aside>
            <button class="hideShow" onclick="hideShow(this)"><strong>⮞ </strong>Main Menu</button>
            <div class="asideMain">
                <form action="/search/">
                    <input type="text" required="required" placeholder="Search…" name="q">
                    <button type="submit">🔎︎</button>
                </form>
                <hr>
                <?php
                    populateAside($id, $lang, $v);
                ?>
                <button class="small" onclick="increaseFontSize()">Increase Font Size</button>
                <button class="small" onclick="decreaseFontSize()">Decrease Font Size</button>
                <button class="small" onclick="swapPalettes()">Swap Color Palette</button>
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
    <script src="/js/jquery/jquery-3.6.0.min.js"></script>
    <script src="/js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Reading Order Drivers -->
    <script src="/js/readingorders.js"></script>
    <!-- Core Site Drivers -->
    <script src="/js/main.js"></script>
    <!-- Reader Drivers -->
    <script src="/js/read.js"></script>
    <script src="/js/lineselection/initlines.js"></script>
    <script src="/js/slideshow.js"></script>
    <!-- Modal Drivers -->
    <script src="/js/modal.js"></script>
    <!-- Ruffle (Flash Driver) -->
    <script src="/js/ruffle/ruffle.js"></script>
    <!-- Unused
    <script src="/js/compare/compare.js"></script>
    -->
</body>

</html>