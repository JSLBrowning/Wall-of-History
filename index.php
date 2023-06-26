<!DOCTYPE html>
<?php

?>
<html lang="en">
<script>
    // If localStorage.colorPreference == light, set light class on html.
    if (localStorage.colorPreference == "light") {
        document.documentElement.classList.add('light');
    } else if (localStorage.colorPreference == "dark") {
        document.documentElement.classList.add('dark');
    } else {
        // Check browser for dark mode preference.
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
            localStorage.setItem("colorPreference", "dark");
            sessionStorage.setItem("TestItem", "TestValue");
            localStorage.setItem("TestItem", "TestValue");
        } else {
            document.documentElement.classList.add('light');
            localStorage.setItem('colorPreference', 'light');
        }
    }
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- STANDARD -->
    <meta property="og:url" content="https://wallofhistory.com/" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Wall of History">
    <meta property="og:title" content="Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp2.png" />
    <meta property="og:image:alt" content="Wall of History: The Complete BIONICLE Legend" />
    <meta name="theme-color" content="#938170">
    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
    <meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
    <meta name="twitter:image" content="https://wallofhistory.com/img/ogp2.png" />
    <meta name="twitter:image:alt" content="Wall of History: The Complete BIONICLE Legend" />
    <!-- END OF OGP DATA -->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/modal.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Wall of History</title>
</head>

<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v8.0" nonce="sckJu8Ly"></script>

    <header>
        <a class="chip-wrapper" href="https://www.maskofdestiny.com/"><img class="chip-img" alt="Mask of Destiny" title="Mask of Destiny" src="/img/chips/mod.webp" width="64" height="64"></a>
        <a href="/"><img src="../img/headers/Faber-Files-Bionicle-logo-Transparent.png"></a>
        <p>
            <a class="small" href="/read/">Contents</a> |
            <a class="small" href="/reference/">Reference</a> |
            <a class="small" href="/about/">About</a> |
            <a class="small" href="https://blog.wallofhistory.com">Blog</a> |
            <a class="small" href="https://www.maskofdestiny.com/news/tags/wall-of-history">News</a> |
            <a class="small" href="/contact/">Contact</a>
        </p>
        <input type="text" placeholder="Search…">
    </header>
    <main>
        <!--
            HOW TO BUILD THE HOMEPAGE.
            1. Parse config.json, get mainWork and additionalTiles.
            2. For mainWork, create article. If route, get route metadata and create button. If story, get metadata, find associated routes, and create button.
            3. For additional tiles, generate a deck of medium cards.
            4. Create invisArticle (footer?) for social media links, et cetera.
        -->

        <?php
        // Include database connection and populate functions.
        include('./php/db_connect.php');
        include('./php/populate.php');

        // Get relevant variables from config.json.
        $config = getJSONConfigVariables();
        $mainWork = $config["mainWork"];
        $additionalTiles = $config["additionalTiles"];
        $socials = $config["socials"];

        // Check if mainWork is a route or a story.
        $main_work_query = "SELECT route_id FROM shin_routes WHERE route_id = '$mainWork' OR route_name = '$mainWork'";
        $main_work_result = $mysqli->query($main_work_query);

        // If above returns a result, the main work is that route. If it returns nothing, the main work must be a story.
        if (mysqli_num_rows($main_work_result) == 1) {
            // echo "<h1>Main work is a route.</h1>";
        } else if (mysqli_num_rows($main_work_result) == 0) {
            // echo "<h1>Main work is a story.</h1>";
        } else {
            // echo "<h1>Something went wrong.</h1>";
        }
        ?>




        <article>
            <div class="video__article">
                <video poster="img/video2.webp" controls>
                    <source src="/img/Wall%20of%20History%20Ad.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <script>
                // If no localStorage is empty, set above video to autoplay.
                if (window.localStorage.length == 0) {
                    document.getElementsByTagName("video")[0].autoplay = true;
                } else {
                    document.getElementsByTagName("video")[0].autoplay = false;
                }
            </script>
            <?php
            echo populateStatic("");
            ?>
        </article>
        <h2 style="text-align: center;">Selected Contents</h2>

        <div class="deck">
            <?php
            // Function to build a medium card. Takes an array as an argument.
            function buildMediumCard($tileContents)
            {
                // Okay, what do we NEED? Need an href, a title, a snippet, and ideally...
                // word count, publish date, version, et cetera (details).

                include('./php/db_connect.php');

                // Get "tile" contents.
                $tile = $tileContents["tile"];
                if (str_contains($tile, "[s:")) {
                    // Strip off first three characters ("[s:"), then strip off last character ("]".
                    $semantic_tag = substr($tile, 3, -1);

                    // Get identification data.
                    $semantic_data = translateFromSemantic($semantic_tag);

                    // Get content data.
                    $content_data = getContentData($semantic_data["id"], $semantic_data["v"], $semantic_data["lang"]);

                    // If len(content_data) > 1, build card using version 1, but list all version_titles in a comma-delimited div.versions.
                    if (count($content_data) == 1) {
                        echo "<a class='card medium__card' href='/read/?s=$semantic_tag'>";
                        echo "<div class='card__text'>";
                        echo "<h3>" . $content_data[0]["content_title"] . "</h3>";
                        echo "<div class='versions'>";
                        echo "<p>" . $content_data[0]["version_title"] . "</p>";
                        echo "</div>";
                        echo "<p>" . $content_data[0]["content_snippet"] . "</p>";
                        echo "</div>";
                        echo "</a>";
                    }
                }

                if (str_contains($tile, "[type:")) {
                    // Strip off first six characters ("[type:"), then strip off last character ("]".
                    $type_tag = substr($tile, 6, -1);

                    $content_data = getContentData(getTypeRepresentative($type_tag));
                    $plural_tag = pluralizeTypeTag($type_tag);

                    $Parsedown = new Parsedown();

                    // If len(content_data) > 1, build card using version 1, but list all version_titles in a comma-delimited div.versions.
                    if (count($content_data) > 0) {
                        // Get image based on content_data.
                        echo "<a class='card medium__card' href='/read/?t=$type_tag'>";
                        echo "<div class='card__text'>";
                        echo "<h3>" . $plural_tag . "</h3>";
                        echo "<p>" . $Parsedown->text($tileContents["description"]) . "</p>";
                        echo "</div>";
                        echo "</a>";
                    }
                }
            }


            function getTypeRepresentative($type)
            {
                include('./php/db_connect.php');

                // How to account for null?
                $query = "SELECT shin_tags.content_id, shin_tags.content_version, shin_tags.content_language, shin_metadata.release_date, shin_metadata.chronology FROM shin_tags JOIN shin_metadata ON shin_tags.content_id=shin_metadata.content_id WHERE tag_type='type' AND tag='$type' AND release_date IS NOT NULL ORDER BY release_date, chronology ASC LIMIT 1;";
                // Add relative chronology values to *Chronicles* novels.
                $result = $mysqli->query($query);

                if (mysqli_num_rows($result) == 1) {
                    $row = $result->fetch_assoc();
                    return $row["content_id"];
                } else {
                    return null;
                }
            }


            


            foreach ($additionalTiles as $additionalTile) {
                // echo populateMedium($tile);
                /**
                 * Card builder would need...
                 * - type=null, s=null, id.v.lang=null...
                 * - override=[
                 *    - title=null, subtitle=null, description=null, image=null, href=null, badge=null
                 * ]
                 */
                buildMediumCard($additionalTile);
            }
            ?>
        </div>
        <?php
        function buildSocials($socials)
        {
            echo "<div class='social'>";

            foreach ($socials as $key => $value) {
                if ($key == "discord") {
                    echo "<a href=\"$value\" width=\"1.5rem\" height=\"1.5rem\"><img src=\"../img/index/Discord-Logo-White.png\" width=\"1.5rem\" height=\"1.5rem\">";
                } else if ($key == "facebook") {
                    echo "<a href=\"https://www.facebook.com/$value\" width=\"1.5rem\" height=\"1.5rem\"><img src=\"../img/index/f_logo_RGB-White_1024.png\" width=\"1.5rem\" height=\"1.5rem\">";
                } else if ($key == "instagram") {
                    echo "<a href=\"https://www.instagram.com/$value\" width=\"1.5rem\" height=\"1.5rem\"><img src=\"../img/index/white-instagram-logo-transparent-background.png\" width=\"1.5rem\" height=\"1.5rem\">";
                } else if ($key == "reddit") {
                    echo "<a href=\"https://www.reddit.com/$value\" width=\"1.5rem\" height=\"1.5rem\"><img src=\"../img/index/reddit_share_silhouette_128.png\" width=\"1.5rem\" height=\"1.5rem\">";
                } else if ($key == "twitter") {
                    echo "<a href=\"https://twitter.com/$value\" width=\"1.5rem\" height=\"1.5rem\"><img src=\"../img/index/Twitter_Social_Icon_Circle_White.png\" width=\"1.5rem\" height=\"1.5rem\">";
                } else if ($key == "youtube") {
                    echo "<a href=\"https://www.youtube.com/@$value\" width=\"1.5rem\" height=\"1.5rem\"><img src=\"../img/index/yt_logo_mono_dark.png\" width=\"1.5rem\" height=\"1.5rem\">";
                }
            }

            echo "</div>";
        }

        buildSocials($socials);
        ?>
    </main>
    <!-- modal -->
    <div id="myModal" class="modal">
        <!-- modal content -->
        <div class="modal-content modal-content-center">
            <div id="modal-data">
                <p>No information is available at this time.</p>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="js/jquery/jquery-3.6.3.min.js"></script>
    <script src="js/jquery/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <!-- Core Site Drivers -->
    <script src="js/main.js"></script>
    <!-- <script src="js/palette.js"></script> -->
    <!-- Reader Drivers -->
    <script src="js/readingorders.js"></script>
    <!-- Modal Drivers -->
    <script src="js/modal.js"></script>
</body>

</html>