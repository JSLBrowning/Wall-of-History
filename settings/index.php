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
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
	<meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
	<meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- end of OGP data -->
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">
    <title>Settings | Wall of History</title>
</head>
<body>
    <header>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <p><a style="cursor: pointer;" onclick="jumpTo()">Read</a> | <a href="/read/">Contents</a> | <a href="/reference/">Reference</a> | <a href="/search/">Search</a> | <a href="/about/">About</a> | <a href="https://blog.wallofhistory.com/">Blog</a> | <a href="/contact/">Contact</a></p>
    </header>
    <main>
        <h2><a onclick="resetReadingOrder()">Reset to Default</a></h2>
        <!--
        <h2><a onclick="uncheckGreg()">Uncheck Greg</a></h2>
        <h1 style="margin-bottom: 0.25em;">Select</h1>
        TO-DO: jQuery control group for tag selections, language preference
        <h1 style="margin: 0.25em 0em;">Language Preference</h1>
        <div class="checkbuttons">
            <button onclick="localStorage.setItem('WallofHistoryLanguageList', 'en,es');
            alert('Your language preference has been updated!')">English</button>
            <button onclick="localStorage.setItem('WallofHistoryLanguageList', 'es,en');
            alert('¡Tu preferencia de idioma ha sido actualizada!')">Español</button>
        </div>
        <h1 style="margin: 0.25em 0em;">Sort</h1>
        -->
        <?php
            include("..//php/populate.php");
            populateSettings();
        ?>
        <button id="submit" style="background-color: #0A0A0A; position: fixed; bottom: 1em; right: 1em;">Submit!</button>
        </main>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="/js/main.js"></script>
    <!-- Fix modal error on settings page, possibly others. -->
    <script src="/js/settings.js"></script>
</body>
</html>