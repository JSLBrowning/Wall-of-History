<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <?php 
        include("..//php/populate.php");
        $id = $_GET["id"];
        populateHead($id);
    ?>
    <link rel='stylesheet' type='text/css' href='/css/main.css'>
    <link rel='stylesheet' type='text/css' href='/css/modal.css'>
    <link rel='stylesheet' type='text/css' href='/css/read.css'>
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
        if(count($_GET)) {
            $id = $_GET["id"];
        } else {
            $id = "0";
        }
        loadContent($id);
        ?>

        <?php
        /*
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
        */
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