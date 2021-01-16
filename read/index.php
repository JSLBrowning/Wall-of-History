<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
    include("..//php/db_connect.php");
        
    if (count($_GET) == 1) {
        // Create selection statement.
        $sql = "SELECT head FROM wall_of_history_contents WHERE id = '" . $_GET["id"] . "'";
            
        // Perfom selection.
        $result = $mysqli->query($sql);
                    
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo array_shift($row);
            }
        }
    } else {
        echo "<meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
        <meta http-equiv='X-UA-Compatible' content='ie=edge'>
        <link rel='icon' href='../favicon.ico' type='image/x-icon'>
    
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='stylesheet' type='text/css' href='/css/main.css'>
        <link rel='stylesheet' type='text/css' href='/css/modal.css'>
        <link rel='stylesheet' type='text/css' href='/css/read.css'>
        <title>Table of Contents | Wall of History</title>";
    }
    ?>
    <link rel='stylesheet' type='text/css' href='/css/modal.css'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
</head>
<body>
    <header>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <p>
        <?php
            if (count($_GET) == 1) {
                echo "<a href=\"/read/\">Contents</a>";
            } else {
                echo "<a onclick=\"jumpTo()\" style=\"cursor: pointer;\">Read</a>";
            }
        ?>
         | <a href="/reference/">Reference</a> | <a href="/search/">Search</a> | <a href="/about/">About</a> | <a href="https://blog.wallofhistory.com/">Blog</a> | <a href="/contact/">Contact</a></p>
    </header>
    <main>
        <?php
        include("..//php/populate.php");
        loadContent($_GET["id"]);

        /*
        if (count($_GET) == 1) {
            include("..//php/db_connect.php");
            $sql = "SELECT childless, content FROM wall_of_history_contents WHERE id='" . $_GET["id"] . "'";
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                if ($row["childless"] == 1) {
                    echo $row["content"];
                } else {
                    $sql = "SELECT fulltitle FROM wall_of_history_contents WHERE id='" . $_GET["id"] . "'";
                    $result = $mysqli->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ((int)$_GET["id"] < 13) {
                                echo "<h2><a onclick='window.location.href=\"/read/\"'>Back</a></h2>";
                            } else {
                                echo "<h2><a onclick='getParent()'>Back</a></h2>";
                            }
                            echo "<h1 style='margin-bottom: 0.5em;'>" . $row["fulltitle"] . "</h1>";
                        }
                    }

                    try {
                        error_reporting(E_ERROR | E_PARSE);
                        $dir = '../doc/' . $_GET['id'] . '/';
                        $thelist = "";
                        if ($handle = opendir($dir)) {
                            while (false !== ($file = readdir($handle))) {
                                if ($file != "." && $file != "..") {
                                    $thelist .=  '<h2 style="text-align: center; margin-top: -1em; margin-bottom=0.5em;"><a href="' . $dir . $file . '">Download ' . $file . '</a></h2>';
                                    echo $thelist;
                                }
                            }
                            closedir($handle);
                        }
                    } catch ( \Exception $e ) {
                        
                    }

                    $sql = "SELECT id, parent, title, childless FROM wall_of_history_contents WHERE parent='" . $_GET["id"] . "' ORDER BY id ASC";
                    $result = $mysqli->query($sql);
                    if ($result->num_rows > 0) {
                        $a = array();
                        $level = $_GET["id"];

                        while ($row = mysqli_fetch_assoc($result)) {
                            foreach ($row as $i => $value) {
                                if ($value == "") $row[$i] = 'NULL';
                            }
                            array_push($a, $row);
                        }

                        function r($a, $level)
                        {
                            $r = '';
                            foreach ($a as $i) {
                                if ($i['parent'] == $level) {
                                    $r = $r . "<li>". "<a href='/read/?id=" . $i['id'] . "'>" . $i["title"] . " ↗</a>" . "</li>";
                                }
                            }
                            return ($r == '' ? '' : "<ol class='collapsibleList' id='sortable'>" . $r . "</ol>");
                        }
                        print r($a, $level);
                    }
                    $mysqli->close();
                }
            }
        } else {
            include("..//php/db_connect.php");

            echo "<h1>Table of Contents</h1>";
            echo "<h2 style='margin-bottom: 0.5em;'><a onclick='window.location.href=\"/settings/\"'>Settings</a></h2>";
            // Create selection statement.
            // $sql = "SELECT id, parent, fulltitle AS title, path FROM wall_of_history_contents WHERE childless=1 ORDER BY id ASC";
            $sql = "SELECT id, parent, title, childless FROM wall_of_history_contents ORDER BY id ASC";

            // Perfom selection.
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                $a = array();
                $level = 'NULL';

                while ($row = mysqli_fetch_assoc($result)) {
                    foreach ($row as $i => $value) {
                        if ($value == "") $row[$i] = 'NULL';
                    }
                    array_push($a, $row);
                }

                // foreach($a as $i => $value) {
                // $a[$i]["parent"] = 'NULL';
                // }

                function r($a, $level)
                {
                    $r = '';
                    foreach ($a as $i) {
                        if ($i['parent'] == $level) {
                            $r = $r . "<li>". "<a href='/read/?id=" . $i['id'] . "'>" . $i["title"] . " ↗</a>" . "</li>";
                        }
                    }
                    return ($r == '' ? '' : "<ol id='sortable'>" . $r . "</ol>");
                }
                print r($a, $level);
            }
            $mysqli->close();
        }
        */
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
    <script src="/js/collapsible.js"></script>
    <script src="/js/indeterminate.js"></script>
    <script src="/js/language.js"></script>
    <script src="/js/slideshow.js"></script>
    <script>
        if ($("#sortable").length > 0) {
            $(".savefile").hide();
            $(".nav").hide();
        }
    </script>
</body>

</html>