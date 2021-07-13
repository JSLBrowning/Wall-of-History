<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/reference/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Reference | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Reference | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/modal.css">
    <link rel="stylesheet" type="text/css" href="/css/reference.css">
    <title><?php 
    if (count($_GET) == 1) {
        include("..//php/db_connect.php");
        
        // Create selection statement.
        $sql = "SELECT name FROM wall_of_history_reference WHERE `name` COLLATE UTF8_GENERAL_CI LIKE '%" . $_GET["id"] . "%'";
        
        // Perfom selection.
        $result = $mysqli->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo str_replace("</em>", "", str_replace("<em>", "", array_shift($row)));
            }
        }
    } else {
        echo "Reference";
    }
    ?> | Wall of History</title>
</head>
<body>
    <header>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <p><a style="cursor: pointer;" onclick="jumpTo()">Read</a> | <a href="/read/">Contents</a> | <a href="/search/">Search</a> | <a href="/about/">About</a> | <a href="https://blog.wallofhistory.com/">Blog</a> | <a href="/contact/">Contact</a></p>
    </header>
    <main>
        <?php
        if (count($_GET) == 1) {
            echo "<h2><a onclick='window.location.href=\"/reference/\"'>Reference</a></h2>";

            include("..//php/db_connect.php");
            $sql = "SELECT name, content FROM wall_of_history_reference WHERE name='" . $_GET["id"] . "'";
            $result = $mysqli->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo $row["content"];
            }
        } else {
            echo "<h1>Reference</h1>";

            include("..//php/db_connect.php");

            // Create selection statement.
            // $sql = "SELECT id, parent, fulltitle AS title, path FROM wall_of_history_contents WHERE childless=1 ORDER BY id ASC";
            $sql = "SELECT name FROM wall_of_history_reference ORDER BY name ASC";

            // Perfom selection.
            $result = $mysqli->query($sql);

            // $sql = "SELECT name AS fulltitle, content FROM wall_of_history_reference WHERE (`name` LIKE '%".$query."%') OR (`content` LIKE '%".$query."%')";

            // Perfom selection.
            // $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                echo "<ol id='sortable' style='list-style-type: none;'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li><a href='/reference/?id=" . $row["name"] . "'>" . $row['name'] . "</a></li>";
                }
                echo "</ol>";
            } else {
                echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
            }

            "<p><a href='/reference/?id=" . $row["name"] . "'>";
        }
        ?>
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
    <script src="/js/slideshow.js"></script>
</body>

</html>