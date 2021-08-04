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
        if(count($_GET)) {
            if(isset($_GET["s"])) {
                echo "Working on it…";
            } else {
                if(isset($_GET["id"])) {
                    $id = $_GET["id"];

                    if (is_numeric($id)) {
                        if ((int)$id < 99999) {
                            include("..//php/db_connect.php");
        
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

                if(isset($_GET["lang"])){
                    $lang = $_GET["lang"];
                } else {
                    $lang = "en";
                }
                
                if(isset($_GET["v"])) {
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
    <link rel='stylesheet' type='text/css' href='/css/modal.css'>
    <link rel='stylesheet' type='text/css' href='/css/read.css'>
    <link rel='stylesheet' type='text/css' href='/css/contents.css'>
    <?php
        addCSS($id, $lang, $v);
    ?>
</head>
<body>
    <header>
        <?php
            loadHeader($id, $lang, $v);
        ?>
    </header>
    <aside>
        <button id="navigationButton">&#9776;</button>
        <button id="settingsButton">&#9881;</button>
        <button id="paletteSwapButton" onclick="swapPalettes()">☀</button>
        <button id="paletteSwapButton" onclick="increaseFontSize()">↑</button>
        <button id="paletteSwapButton" onclick="decreaseFontSize()">↓</button>
        <!-- MAIN NAVIGATION MENU MODAL -->
        <div id="navigationModal" class="modal">
            <div class="modal-content">
                <span id="navigationClose">&times;</span>
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
                <p><a href="/contact/">Contact</a></p>
            </div>
        </div>
        <!-- SETTINGS MENU MODAL (WILL REDIRECT TO GLOBAL SETTINGS PAGE ON GLOBAL TABLE OF CONTENTS (NO ID PARAMETER)) -->
        <div id="settingsModal" class="modal">
            <div class="modal-content">
                <span id="settingsClose">&times;</span>
                <?php
                    include("..//php/populatesettingsmodal.php");
                    populateSettingsModal($id, $v, $lang);
                ?>
            </div>
        </div>
        <!-- DOWNLOAD BUTTON -->
        <!-- The a wrapper doesn't seem to be affecting CSS at the moment, but it might be a good idea to give it a unique id anyway, JUST to be sure… -->
        <a id="downloadLink" href="/doc/BIONICLE Year One.pdf" download="BIONICLE Year One.pdf" target="_blank" style="display: none;"><button id="downloadButton">↓</button></a>
        <!-- <button id="clearStandaloneButton">Clear Standalone</button> -->
    </aside>
    <main>
        <?php
        loadContent($id, $lang, $v);
        ?>

        <?php
        include("..//php/db_connect.php");

        // Create selection statement.
        $sql = "SELECT name FROM wall_of_history_reference";

        // Perfom selection.
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $b = array();

            while ($row = mysqli_fetch_assoc($result)) {
                array_push($b, $row);
            }

            function rd($b)
            {
                $r = '';
                foreach ($b as $i) {
                    $r = $r . "<li>" . $i['name'] . "</li>";
                }
                return ($r == '' ? '' : "<ol id='referenceitems' style='list-style-type:none' hidden='true'>" . $r . "</ol>");
            }
            print rd($b);
        }
        $mysqli->close();
        ?>
        <div style="padding: 4px;"></div>
        <div class="savefile" style="display:none;">
            <button type="savefilebutton" onclick="savePlace()">Save Place</button>
            <button type="savefilebutton" onclick="loadPlace()">Load Place</button>
        </div>
        <div class="nav" style="display:none">
            <button type="navbutton" onclick="goBack()" id="backbutton" style="display:none">←</button>
            <button type="navbutton" onclick="goForward()" id="forwardbutton" style="display:none">→</button>
        </div>
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
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
    <script src="/js/readingorders.js"></script>
    <script src="/js/read.js"></script>
    <script>downloadContent()</script>
    <script src="/js/modal.js"></script>
    <script src="/js/indeterminate.js"></script>
    <script src="/js/language.js"></script>
    <script src="/js/slideshow.js"></script>
    <script>
        // ?
        if ($("#sortable").length > 0) {
            $(".savefile").hide();
            $(".nav").hide();
        }
    </script>
    <!-- <script src="/js/palette.js"></script> -->
</body>

</html>