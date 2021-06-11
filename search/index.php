<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/search/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Search | Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Search | Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        form{
            margin: 1em;
            padding: 0em;
            display: flex;
            justify-content: center;
        }

        form input{
            margin: 0px;
            margin-top: 12px;
            margin-bottom: 6px;
            border: none;
            height: 24px;
            width: 60%;
        }

        form button{
            margin: 0em;
            margin-top: 12px;
            margin-bottom: 6px;
            border: none;
            border-radius: 0px;
            background-color: #333435;
            height: 24px;
            width: 30%;
            font-size: 1em;
            text-shadow: 2px 2px 5px #000;
            box-shadow: none;
        }

        main hr{
            margin-left: 0em;
            margin-right: 0em;
        }

        main h3{
            margin-top: 1em;
            text-align: left;
            font-size: medium;
            cursor: pointer;
        }

        main h3:hover{
            color: #cccccc;
            text-decoration: none;
        }

        main h3:active{
            color: #99999a;
            text-decoration: none;
        }

        main h3:visited{
            color: #fff;
            text-decoration: none;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <header>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <p><a style="cursor: pointer;" onclick="jumpTo()">Read</a> | <a href="/read/">Contents</a> | <a href="/reference/">Reference</a> | <a href="/about/">About</a> | <a href="https://blog.wallofhistory.com/">Blog</a> | <a href="/contact/">Contact</a></p>
    </header>
    <main>
        <form action="/search/" style="margin: auto;">
            <input type="text" placeholder="Search…" name="q">
            <button type="submit">🔍</button>
        </form>
        <!-- RESULTS ORDER:
        Reference collections with exact name.
        Story pages with name in title.
        Story pages with name in content.
        -->
        <hr>
        <?php
        if (count($_GET) == 1) {
            include("..//php/db_connect.php");

            $query = $_GET['q'];

            $sql = "SELECT name, content FROM wall_of_history_reference WHERE (`name` LIKE '%".$query."%') OR (`content` LIKE '%".$query."%')";

            // Perfom selection.
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<h3 onclick = \"window.location.href='/reference/?id=" . $row["name"] . "';\">" . $row['name'] . "</h3>";
                    echo "<p> Reference page for " . $row['name'] . ".</p>";
                }
            }

            $query = $_GET['q'];
            // gets value sent over search form

            $sql = "SELECT id, fulltitle, description, content FROM wall_of_history_contents WHERE (`fulltitle` LIKE '%".$query."%') OR (`content` LIKE '%".$query."%')";

            // Perfom selection.
            $result = $mysqli->query($sql);

            // $sql = "SELECT name AS fulltitle, content FROM wall_of_history_reference WHERE (`name` LIKE '%".$query."%') OR (`content` LIKE '%".$query."%')";

            // Perfom selection.
            // $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<h3 onclick = \"window.location.href='/read/?id=" . $row["id"] . "';\">" . $row['fulltitle'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                }
            } else {
                echo "<p>Your search returned zero results.</p>";
            }
        } else {
            echo "<p>Please enter a search term.</p>";
        }
        ?>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
    <script>
        availableTags = ["Tahu", "Onua", "Mata Nui"];

        $("input").autocomplete({
            source: availableTags
        });
    </script>
</body>
</html>