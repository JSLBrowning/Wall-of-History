<?php
    // Some of the pages that require PHP — such as /read and /reference — are very messy right now, on account of the fact that all the PHP necessary to populate them with content is actually in those pages.
    // As such, all of this code will be relocated here, cleaned up, and streamlined.
    // The aforementioned pages will henceforth call the functions defined here.
    // https://stackoverflow.com/questions/8104998/how-to-call-function-of-one-php-file-from-another-php-file-and-pass-parameters-t

    include("..//php/db_connect.php");

    function loadContent($id){
        if (count($_GET) == 1) {
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
    }
?>