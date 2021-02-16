<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <?php 
        include("..//php/populate.php");
        if(count($_GET)) {
            $id = $_GET["id"];
            if (is_numeric($id)) {
                if ((int)$id < 99999) {
                    include("..//php/db_connect.php");

                    $sql = "SELECT id FROM woh_metadata WHERE chronology=" . $id . " LIMIT 1";
                    $result = $mysqli->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                        echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://wallofhistory.com/read/?id=" . $id . "'\" />\n";
                    }
                }
            }
        } else {
            $id = "0";
        }
        populateHead($id);
    ?>
    <link rel='stylesheet' type='text/css' href='/css/main.css'>
    <link rel='stylesheet' type='text/css' href='/css/modal.css'>
    <link rel='stylesheet' type='text/css' href='/css/read.css'>
    <link rel='stylesheet' type='text/css' href='/css/tahu.css'>
    <link rel='stylesheet' type='text/css' href='/css/contents.css'>
</head>
<body>
    <header>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
    </header>
    <aside>
        <!-- MAIN NAVIGATION MENU MODAL -->
        <button id="navigationButton">&#9776;</button>
        <div id="navigationModal" class="modal">
            <div class="modal-content">
                <span id="navigationClose">&times;</span>
                <p>
                <?php
                    if (count($_GET) == 1) {
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
        <button id="settingsButton">&#9881;</button>
        <div id="settingsModal" class="modal">
            <div class="modal-content">
                <span id="settingsClose">&times;</span>
                <p>Blah blah blah…</p>
            </div>
        </div>
        <!-- DOWNLOAD BUTTON -->
        <button id="downloadButton" onclick="alert('Gun.');">↓</button>
    </aside>
    <main>
        <?php
        if(count($_GET)) {
            $id = $_GET["id"];
            /* This shouldn't actually be necessary if the code in the head works.
            But... Might be good to have it anyway, so the page won't be blank in the split second it's visible.
            if (is_numeric($id)) {
                if ((int)$id < 99999) {
                    include("..//php/db_connect.php");

                    $sql = "SELECT id FROM woh_metadata WHERE chronology=" . $id . " LIMIT 1";
                    $result = $mysqli->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["id"];
                    }
                }
            }
            */
        } else {
            $id = "0";
        }
        loadContent($id);
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
        <div class="savefile">
            <button type="savefilebutton" onclick="savePlace()">Save Place</button>
            <button type="savefilebutton" onclick="loadPlace()">Load Place</button>
        </div>
        <div class="nav">
            <button type="navbutton" onclick="goBack()" id="backbutton">←</button>
            <button type="navbutton" onclick="goForward()" id="forwardbutton">→</button>
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
</body>

</html>