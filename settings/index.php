<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- Standard/Facebook -->
    <meta property="og:url" content="https://wallofhistory.com/settings/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <meta property="og:video" content="https://wallofhistory.com/img/Wall%20of%20History%20Ad.mp4" />
    <meta property="og:video:url" content="https://wallofhistory.com/img/Wall%20of%20History%20Ad.mp4" />
    <meta property="og:video:secure_url" content="https://wallofhistory.com/img/Wall%20of%20History%20Ad.mp4" />
    <meta property="og:video:type" content="video/mp4" />
    <meta property="og:video:width" content="2556" />
    <meta property="og:video:height" content="1024" />
    <!-- Twitter -->
    <meta name="twitter:card" content="player" />
    <meta name="twitter:title" content="Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
	<meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:player" content="https://wallofhistory.com/player/container.html" />
	<meta name="twitter:player:width" content="2556" />
    <meta name="twitter:player:height" content="1024" />
	<meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- end of OGP data -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <title>Settings | Wall of History</title>
</head>
<body>
    <header>
        <script></script>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <p><a style="cursor: pointer;" onclick="jumpTo()">Read</a> | <a href="/read/">Contents</a> | <a href="/reference/">Reference</a> | <a href="/search/">Search</a> | <a href="/about/">About</a> | <a href="https://blog.wallofhistory.com/">Blog</a> | <a href="/contact/">Contact</a></p>
    </header>
    <main>
        <h2><a onclick="resetReadingOrder()">Reset to Default</a></h2>
        <h1 style="margin-bottom: 0.25em;">Select</h1>
        <div class="checkbuttons">
            <button onclick="uncheckEverything()">Uncheck Everything</button>
            <button onclick="checkEverything()">Check Everything</button>
            <button onclick="uncheckAnimations()">Uncheck Animations</button>
            <button onclick="checkAnimations()">Check Animations</button>
            <button onclick="uncheckBlogs()">Uncheck Blogs</button>
            <button onclick="checkBlogs()">Check Blogs</button>
            <button onclick="uncheckCards()">Uncheck Cards</button>
            <button onclick="checkCards()">Check Cards</button>
            <button onclick="uncheckComics()">Uncheck Comics</button>
            <button onclick="checkComics()">Check Comics</button>
            <button onclick="uncheckDiaries()">Uncheck Diaries</button>
            <button onclick="checkDiaries()">Check Diaries</button>
            <button onclick="uncheckGames()">Uncheck Games</button>
            <button onclick="checkGames()">Check Games</button>
            <button onclick="uncheckGrowing()">Uncheck Growing Reader Books</button>
            <button onclick="checkGrowing()">Check Growing Reader Books</button>
            <button onclick="uncheckMovies()">Uncheck Movies</button>
            <button onclick="checkMovies()">Check Movies</button>
            <button onclick="uncheckNovels()">Uncheck Novels</button>
            <button onclick="checkNovels()">Check Novels</button>
            <button onclick="uncheckPodcasts()">Uncheck Podcasts</button>
            <button onclick="checkPodcasts()">Check Podcasts</button>
            <button onclick="uncheckSerials()">Uncheck Serials</button>
            <button onclick="checkSerials()">Check Serials</button>
            <button onclick="uncheckShorts()">Uncheck Short Stories</button>
            <button onclick="checkShorts()">Check Short Stories</button>
            <button onclick="uncheckWeb()">Uncheck Web Fiction</button>
            <button onclick="checkWeb()">Check Web Fiction</button>
        </div>
        <h1 style="margin: 0.25em 0em;">Language Preference</h1>
        <div class="checkbuttons">
            <button onclick="localStorage.setItem('WallofHistoryLanguageList', 'en,es');
            alert('Your language preference has been updated!')">English</button>
            <button onclick="localStorage.setItem('WallofHistoryLanguageList', 'es,en');
            alert('¡Tu preferencia de idioma ha sido actualizada!')">Español</button>
        </div>
        <h1 style="margin: 0.25em 0em;">Sort</h1>
        <?php
            include("..//php/db_connect.php");

            // Create selection statement.
            $sql = "SELECT id, parent, fulltitle AS title, mediatype FROM wall_of_history_contents WHERE childless=1 ORDER BY id ASC";
            // $sql = "SELECT id, parent, title, childless FROM wall_of_history_contents ORDER BY id ASC";

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
                
                // Remove parent stuff?
                foreach($a as $i => $value) {
                    $a[$i]["parent"] = 'NULL';
                }

                function r($a, $level)
                {
                    $r = '';
                    foreach ($a as $i) {
                        if ($i['parent'] == $level) {
                            $r = $r . "<li>
                                <input class='" . $i["mediatype"] . "' type='checkbox' name='" . $i['id'] . "' id='" . $i['id'] . "' value='" . $i['id'] . "'>
                                <label for='" . $i['id'] . "'>" . $i['title'] . "<a href='/read/?id=" . $i['id'] . "'>↗</a>" . "</label>" . r($a, $i['id']) . "
                            </li>";
                        }
                    }
                    return ($r == '' ? '' : "<ol id='sortable' style='list-style-type: none;'>" . $r . "</ol>");
                }
                print r($a, $level);
            }
            $mysqli->close();
        ?>
        <button id="submit" style="background-color: #0A0A0A; position: fixed; bottom: 1em; right: 1em;">Submit!</button>
        </main>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
    <script src="/js/settings.js"></script>
</body>
</html>