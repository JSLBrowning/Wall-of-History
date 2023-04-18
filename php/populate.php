<?php


// Include Parsedown.
require("/parsedown-1.7.4/Parsedown.php");


// Get config.json variables.
// Access variables using $GLOBALS['config']['variable']. For example, $GLOBALS['config']['mainWork'].
$path = $_SERVER['DOCUMENT_ROOT'] . "/config/config.json";
$GLOBALS['config'] = json_decode(file_get_contents($path), true);


/****************
 * LABEL TABLES *
 ****************/

// This table is used to translate the language codes used in the database into human-readable language names.
$languages = [
    "en" => "English",
    "es" => "Español",
    "fr" => "Français",
    "de" => "Deutsch",
    "it" => "Italiano",
    "pt" => "Português",
    "ru" => "Русский",
    "ja" => "日本語",
    "ko" => "한국어",
    "zh" => "中文"
];


/**
 * HOW TO BUILD A READER PAGE:
 * 1. Ensure an ID, version, and language were passed to the page.
 * 2. If not, redirect to the table of contents.
 *    a. On table of contents, find tops of trees with chronology values (the chapters, basically).
 *    b. Include a dropdown menu for other options, including media type and release date.
 *    c. If media type, split into the four categories, with main at the top, followed by developmental, promotional, and supplemental.
 *       i. Then have one block for each type, with the title of the block being the type.
 *    d. If release date, find all elements with a release date. If all children of a parent have the same, only display the parent.
 * 3. If so, populate the <head> with OGP data.
 * 4. Populate the <head> with any relevant CSS links.
 * 5. Populate the <body> with the header.
 * 6. Populate the <body> with an article containing the main content (use a createArticle() function, maybe) according to the tags.
 *    a. Populate the titlebox with the...
 *       i. <img>
 *       ii. .title__box__text
 *          1. Parent(s) (if applicable).
 *          2. Title.
 *          3. Subtitle.
 *          4. Creators.
 *       iii. .title__box__buttons
 *       iv. .title__box__tags
 *    b. Populate .extra__content.
 *       i. .version__select
 *       ii. .language__select
 *       iii. any .detail <div>s
 *       iv. .extra__areas for downloads, development material, promotional material, and supplemental material.
 *          1. Use relevant Font Awesome icons for PDF, DOCX, e-book, et cetera, then use default for others.
 *    c. Populate the rest of the block with the main content.
 *       i. If the main content column is empty, attempt to load content from the relevant directory, as specified in config.json.
 *       ii. if (main == null) {$main = loadExternalContent(pathTemplate, UUID, version, language)}
 *       iii. If type is "comic", and folder contains images, create a slideshow, etc.
 * 7. Populate the <body> with a <div.deck> containing children. Can be sorted by…
 *    a. Release date. One list.
 *    b. Type. If *all* children share a certain type, remove it from groupings. Then do this recursively. If all videos are movies, remove the video type. Basically, be specific.
 *       i. If all children are of the same type, don't bother with the type header.
 *       ii. Types should also be collapsible.
 *       iii. "Story" and "ref" should be types?
 *    c. Chronology. Only if all children have a chronology value.
 * X. Run mods.
 *    a. Run any PHP files inside the /mods/ folder.
 *    b. Insert <script> tags for any JS files found in the /mods/ folder before the end of the <body> tag.
 */


/******************
 * TEST FUNCTIONS *
 ******************/

// getRoute($route_id): Take a route ID and return the decoded JSON array.
// Active route will be passed in through cookies.
function getRoute($route_id) {
    include("db_connect.php");

    $query = "SELECT route_main FROM shin_routes WHERE route_id = '$route_id'";
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
        return null;
    } else if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $route = json_decode($row["route_main"], true);
        return $route;
    } else {
        return null;
    }
}


// findNeighbors($route, $id): Take a decoded JSON array, find any object where the passed id is in an object under a "current" key, and return all "previous" and "next" keys.
function findNeighbors($route, $id) {
    foreach ($route as $value) {
        // Check if the array has a "current" key.
        if (array_key_exists("current", $value)) {
            // Check if the "current" key has the passed ID.
            if ($value["current"]["content_id"] == $id) {
                // If so, return the "previous" and "next" keys.
                return [$value["previous"], $value["next"]];
            }
        } else {
            // If not, check if the array has any children.
            if (is_array($value)) {
                // If so, run the function again.
                findNeighbors($value, $id);
            }
        }
    }
}


function findFirstPage($route) {
    foreach ($route as $value) {
        // Check if the array has a "current" key.
        if (array_key_exists("current", $value)) {
            // If so, return the "current" key.
            return $value["current"];
        } else {
            // If not, check if the array has any children.
            if (is_array($value)) {
                // If so, run the function again.
                findFirstPage($value);
            }
        }
    }
}


function getMainContent($id, $version=1, $language="eng") {
    include("db_connect.php");

    $query = "SELECT content_main FROM shin_content WHERE content_id = '$id' AND content_version =$version AND content_language = '$language'";
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
        return null;
    } else if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $content = $row["content_main"];
        echo $content;
    } else {
        return null;
    }
}


/***********************
 * UNIVERSAL FUNCTIONS *
 ***********************/


// This function takes the color palette cookie and returns the appropriate CSS class, inserting it into the <html> tag, ideally before the page even finishes loading for the end user.
function chooseColors()
{
    if (isset($_COOKIE["colorPreference"])) {
        echo "<html lang='en " . $_COOKIE["colorPreference"] . "'>";
    } else {
        echo "<html lang='en'>";
    }
}


// This function returns an array of results when a query returns just one row.
// Can be used for simplifying functions that require several simple queries.
function getData($column, $query)
{
    include("db_connect.php");

    $data = [];
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row[$column]);
        }
    }
    return $data;
}


// This function gets all the basic content data for a given ID, version, and language.
function getContent($id, $v, $lang) {
    include("db_connect.php");

    echo "WIP…";
}


function getImages($id, $v, $lang, $caption) {
    $formats = [".webp", ".jpg", ".jpeg", ".png"];
    $names = ["$id.$v.$lang", "$id.$v", "$id"];
    foreach ($names as $name) {
        foreach ($formats as $format) {
            $image = "/img/story/contents/$name$format";
            if (file_exists(".." . $image)) {
                return "<img src='" . $image . "' alt='" . $caption . "'>";
            }
        }
    }
    return "";
}


function getOGPImages($id, $v, $lang) {
    $formats = [".webp", ".jpg", ".jpeg", ".png"];
    $names = ["$id.$v.$lang", "$id.$v", "$id"];
    foreach ($names as $name) {
        foreach ($formats as $format) {
            $image = "/img/ogp/$name$format";
            if (file_exists(".." . $image)) {
                return $image;
            }
        }
    }
    return "/img/ogp2.png";
}


/***************************
 * END UNIVERSAL FUNCTIONS *
 ***************************/


/********************************
 * CONTENT POPULATION FUNCTIONS *
 * These functions populate the
 * reader page, from top (head)
 * to bottom (main).
 ********************************/


// This function populates the <head> of the page with content-specific OGP data.
function populateHead($id, $lang, $v)
{
    include("db_connect.php");


    $semantic_query = "SELECT tag, detailed_tag FROM shin_tags WHERE tag_type='semantic'";
    $semantic = $mysqli->query($semantic_query);
    while ($row = $semantic->fetch_assoc()) {
        $ids = explode(".", $row["tag"]);
        $detailed_tag = $row["detailed_tag"];
        echo "<p>INSERT INTO shin_tags VALUES ('" . $ids[0] . "', '" . $ids[1] . "', '" . $ids[2] . "', 'semantic', '" . $detailed_tag . "')</p>";
    }


    $result = $mysqli->query("SELECT title, snippet, theme_color FROM story_metadata JOIN story_content ON story_metadata.id = story_content.id WHERE story_metadata.id = \"$id\" AND story_content.content_version = \"$v\" AND story_content.content_language = \"$lang\"");
    $num_rows = mysqli_num_rows($result);
    $image = getOGPImages($id, $v, $lang);

    // If query returns no result, determine whether or not user is on the table of contents, and respond accordingly.
    if ((!($id == 0)) && ($num_rows == 0)) {
        echo "<meta property='og:site_name' content='Wall of History'>
            <meta content='404 | Wall of History' property='og:title'/>
            <meta content='Six heroes. One destiny.' property='og:description'/>
            <meta content='http://www.wallofhistory.com" . $image . "' property='og:image'/>
            <meta content='summary_large_image' name='twitter:card'/>
            <meta content='@Wall_of_History' name='twitter:site'/>
            <meta name='theme-color' content='#938170'>
            <title>404 | Wall of History</title>\n";
        echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://wallofhistory.com/404.html'\"/>\n";
    } else if ($id == 0 && $num_rows == 0) {
        echo "<meta property='og:site_name' content='Wall of History'>
            <meta content='Table of Contents | Wall of History' property='og:title'/>
            <meta content='Six heroes. One destiny.' property='og:description'/>
            <meta content='http://www.wallofhistory.com" . $image . "' property='og:image'/>
            <meta content='summary_large_image' name='twitter:card'/>
            <meta content='@Wall_of_History' name='twitter:site'/>
            <meta name='theme-color' content='#938170'>
            <title>Table of Contents | Wall of History</title>\n";
    }

    // If query does return a result, respond accordingly.
    while ($row = $result->fetch_assoc()) {
        $title = strip_tags($row["title"]);

        // Get full title, if chapter.
        $chapter_query = "SELECT COUNT(tag) AS tags FROM story_tags WHERE id = '$id' AND tag = 'chapter'";
        $chapter_count = getData("tags", $chapter_query);
        if ($chapter_count[0] > 0) {
            $parent_query = "SELECT title FROM story_content JOIN story_reference_web ON story_reference_web.parent_id = story_content.id WHERE story_reference_web.child_id = '" . $id . "' LIMIT 1";
            $parent = strip_tags(getData("title", $parent_query)[0]);
            $title = $title . " | " . $parent;
        }

        echo "<meta property='og:site_name' content='Wall of History'>
            <meta content='" . $title . " | Wall of History' property='og:title'/>
            <meta content='" . strip_tags($row["snippet"]) . "' property='og:description'/>
            <meta content='http://www.wallofhistory.com" . $image . "' property='og:image'/>
            <meta content='summary_large_image' name='twitter:card'/>
            <meta content='@Wall_of_History' name='twitter:site'/>
            <meta name='theme-color' content='#" . $row['theme_color'] . "'>
            <title>" . $title . " | Wall of History</title>\n";
    }
}


// This function populates the head of the page with content-specific CSS links.
function populateCSS($id)
{
    // Type
    include("db_connect.php");
    $sql = "SELECT tag FROM story_tags WHERE id = \"" . $id . "\" AND tag_type = 'type'";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        if (file_exists("..//css/type/" . $row["tag"] . ".css")) {
            echo "<link rel='stylesheet' type='text/css' href='/css/type/" . $row["tag"] . ".css'>\n";
        }
    }

    /*
    When two parent or grandparent CSS IDs dispute, loop through the history stack to find the most recent match.
    If that doesn't work, try the tree of the chronology-- ID for a match.
    Use version numbers to get correct grandparent where applicable (“The Legend of Mata Nui,” for example).
    If THAT doesn't work, default to newer, I GUESS (so the site will GENERALLY keep up where there's a conflict).
    */
    // Grandparent
    $sql = "SELECT parent_id FROM story_reference_web WHERE child_id IN (SELECT parent_id FROM story_reference_web WHERE child_id='$id' AND parent_id NOT IN (SELECT id FROM story_tags WHERE tag='collection'));";
    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 1) {
        $options = array();
        while ($row = $result->fetch_assoc()) {
            array_push($options, $row["parent_id"]);
        }

        if (isset($_COOKIE["historyStack"])) {
            $history = array_reverse(explode(",", $_COOKIE["historyStack"]));
            foreach ($history as $value) {
                if (in_array($value, $options)) {
                    if (file_exists("..//css/id/" . $value . ".css")) {
                        echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $value . ".css'>\n";
                        break;
                    }
                    break;
                }
            }
        }
    } else if ($num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            if (file_exists("..//css/id/" . $row["parent_id"] . ".css")) {
                echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $row["parent_id"] . ".css'>\n";
            }
        }
    }

    // Parent
    // Add above disambiguation code to this.
    // Also, update read.js line ~151.
    $sql = "SELECT parent_id FROM story_reference_web WHERE child_id ='$id' AND parent_id NOT IN (SELECT id FROM story_tags WHERE tag='collection');";
    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 1) {
        $options = array();
        while ($row = $result->fetch_assoc()) {
            array_push($options, $row["parent_id"]);
        }

        if (isset($_COOKIE["historyStack"])) {
            $history = array_reverse(explode(",", $_COOKIE["historyStack"]));

            foreach ($history as $value) {
                if (in_array($value, $options)) {
                    if (file_exists("..//css/id/" . $value . ".css")) {
                        echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $value . ".css'>\n";
                        break;
                    }
                    break;
                }
            }
        }
    } else if ($num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            if (file_exists("..//css/id/" . $row["parent_id"] . ".css")) {
                echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $row["parent_id"] . ".css'>\n";
            }

            // Temporary/hardcoded solution to the multi-grandparent disambiguation problem.
            if (file_exists("..//css/id/override/" . $row["parent_id"] . ".css")) {
                echo "<link rel='stylesheet' type='text/css' href='/css/id/override/" . $row["parent_id"] . ".css'>\n";
            }
        }
    }

    // Self
    if (file_exists("../css/id/" . $id . ".css")) {
        echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $id . ".css'>\n";
    }

    // Overrides
    // Temporary/hardcoded solution to the multi-grandparent disambiguation problem.
    if (file_exists("../css/id/override/" . $id . ".css")) {
        echo "<link rel='stylesheet' type='text/css' href='/css/id/override/" . $id . ".css'>\n";
    }
}


// This function loads a unique header for a page, if it has one.
function loadHeader($id)
{
    include("db_connect.php");
    $sql_header = "SELECT html FROM story_content JOIN story_headers ON story_content.header = story_headers.header_id WHERE story_content.id = '$id' LIMIT 1";
    // Make this recurse up to get parents if none.
    $result_header = $mysqli->query($sql_header);
    $num_rows = mysqli_num_rows($result_header);

    if ($num_rows == 0) {
        echo "<img src=\"/img/headers/Faber-Files-Bionicle-logo-Transparent.png\" alt=\"BIONICLE\" height=\"80\" style=\"cursor: pointer;\" onclick=\"window.location.href='/'\">\n";
    } else {
        while ($row_header = $result_header->fetch_assoc()) {
            echo $row_header["html"];
        }
    }
}


/**
 * MAIN CONTENT FUNCTIONS
 * (Divided due to size.)
 */


// This function gets the parent(s), if any, of the current page, and displays them.
function loadContentParents($id, $v, $title)
{
    include("db_connect.php");

    $parents_query = "SELECT * FROM story_reference_web WHERE child_id=\"$id\" AND child_version=$v";
    $parents = $mysqli->query($parents_query);
    $num_parents = mysqli_num_rows($parents);

    if ($id === "0") {
        echo "<div class='titleBoxText'><h1>Table of Contents</h1></div></section>";
    } else if ((!($id === "0")) && ($num_parents === 0)) {
        echo "<div class='titleBoxText'><h3><a onClick='location.href=\"/read/\"'>BIONICLE</a></h3>";
    } else {
        echo getImages($id, $v, "NULL", $title[0]);
        echo "<div class='titleBoxText'>";

        if ($num_parents === 1) {
            while ($row = $parents->fetch_assoc()) {
                $parent_title_query = "SELECT title FROM story_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
                $parent_title = $mysqli->query($parent_title_query);
                while ($new_row = $parent_title->fetch_assoc()) {
                    echo "<h3><a onClick=\"goTo('" . $row["parent_id"] . "." . $row["parent_version"] . "')\">" . $new_row["title"] . "</a></h3>";
                }
            }
        } else if ($num_parents > 1) {
            echo "<div class='multiparents'><button onclick='carouselBack(this)'><span class='leftarrow'></span></button>";
            while ($row = $parents->fetch_assoc()) {
                $sql_title = "SELECT title FROM story_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
                // ORDER BY chronology, title ASC
                $result_title = $mysqli->query($sql_title);
                while ($row_title = $result_title->fetch_assoc()) {
                    $parentid = $row["parent_id"];
                    echo "<h3><a id='$parentid' onClick=\"goTo('$parentid." . $row["parent_version"] . "')\">" . $row_title["title"] . "</a></h3>";
                }
            }
            echo "<button onclick='carouselForward(this)'><span class='rightarrow'></span></button></div>";
        }
    }
}


function sanitizeContributors($contributors_array)
{
    $sanitized_contributors = array();
    $exploded_contributors = array();
    foreach ($contributors_array as $contributor) {
        array_push($exploded_contributors, explode(" by ", $contributor));
    }

    $contributor_types = array();
    foreach ($exploded_contributors as $contributor) {
        array_push($contributor_types, $contributor[0]);
    }

    $unique_contributor_types = array_unique($contributor_types);
    foreach ($unique_contributor_types as $type) {
        // If count > 1, get all [1] with that type.
        //     Append "and " to last.
        //     Implode with comma.
        //     Add to sanitized array
        $count = 0;
        $latest = array();
        $contributors = array();
        foreach ($exploded_contributors as $contributor) {
            if ($contributor[0] === $type) {
                array_push($contributors, $contributor[1]);
                $latest = implode(" by ", $contributor);
                $count++;
            }
        }

        if ($count > 1) {
            $last = array_pop($contributors);
            $contributors = implode(", ", $contributors);
            $contributors .= " and " . $last;
            array_push($sanitized_contributors, implode(" by ", array($type, $contributors)));
        } else {
            array_push($sanitized_contributors, $latest);
        }
        // Else, pass through to sanitized array as-is.
    }

    return implode(", ", $sanitized_contributors);
}


function loadContentContributors($id)
{
    include("db_connect.php");

    $contributors_query = "SELECT detailed_tag AS tag FROM story_tags WHERE id = \"" . $id . "\" AND (tag_type = 'author')";
    $contributors = $mysqli->query($contributors_query);
    $num_rows = mysqli_num_rows($contributors);
    if ($num_rows == 1) {
        while ($row = $contributors->fetch_assoc()) {
            echo "<h3>" . $row["tag"] . "</h3>";
        }
    }

    if ($num_rows > 1) {
        echo "<h3>";
        $contributors_array = array();
        // Replace with implode.
        while ($row = $contributors->fetch_assoc()) {
            array_push($contributors_array, $row["tag"]);
        }
        sort($contributors_array);
        echo sanitizeContributors($contributors_array) . "</h3>";
    }
}


// This function loads the content for the .story section of a page.
function loadContent($id, $v, $lang)
{
    include("db_connect.php");


    // Determine if this content is divided into pages, and respond accordingly.
    $pages_query = "SELECT COUNT(tag) AS tag_count FROM story_tags WHERE id='$id' AND tag='pages'";
    if (getData("tag_count", $pages_query)[0] > 0) {
        echo "<section class='story pages'><section class='titleBox'>\n";
    } else {
        echo "<section class='story'><section class='titleBox'>\n";
    }

    // Get title (to be displayed later.)
    $title_query = "SELECT title FROM story_content WHERE id = \"" . $id . "\" AND content_language = \"" . $lang . "\" AND content_version = \"" . $v . "\" LIMIT 1";
    $title = getData("title", $title_query);

    loadContentParents($id, $v, $title);

    // Display title.
    if (!($id === "0") && ($title[0] !== "")) {
        echo "<h1>" . $title[0] . "</h1>";
    }

    // Get and display subtitle, if any.
    $subtitle_query = "SELECT subtitle FROM story_content WHERE id = \"" . $id . "\" AND content_version = \"" . $v . "\" AND content_language = \"" . $lang . "\"";
    $subtitle = getData("subtitle", $subtitle_query);
    if (!($id === "0") && ($subtitle[0] != "")) {
        echo "<h2>" . $subtitle[0] . "</h2>";
    }

    loadContentContributors($id);
    echo "</section>";

    // Get main content.
    $sql = "SELECT main FROM story_content WHERE id=\"$id\" AND content_version=\"$v\" AND content_language=\"$lang\"";

    // Display snippet in place of main if main empty (for parent works)?

    // Display main content
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row["main"];
    }

    echo "</section>";
}



function getDetails($id, $primeversion, $lang)
{
    $data_count = 0;
    $output = "<div class='versions'>\n";

    $versionquery = "SELECT version_title FROM story_content WHERE id = '$id' AND content_language = '$lang' AND version_title IS NOT NULL ORDER BY content_version ASC LIMIT 3";
    $versions = getData("version_title", $versionquery);
    if (count($versions) > 0 && count($versions) <= 2) {
        // Increment data_count.
        $data_count++;
        $output .= "<p>" . implode(", ", $versions) . "</p>\n";
    } else if (count($versions) > 2) {
        // Increment data_count.
        $data_count++;
        $output .= "<p>" . implode(", ", array_slice($versions, 0, 2)) . ", OTHERS</p>\n";
    }
    
    $releasequery = "SELECT publication_date FROM story_metadata WHERE id = '$id'";
    $release = getData("publication_date", $releasequery);
    if ($release[0] != "") {
        // Increment data_count.
        $data_count++;
        $output .= "<p>RELEASED " . str_replace("-", "/", implode(", ", getData("publication_date", $releasequery))) . "</p>\n";
    }

    $wordquery = "SELECT ROUND(AVG(word_count), 0) AS word_count FROM story_content WHERE id = '$id' and content_version = '$primeversion'";
    $words = getData("word_count", $wordquery);
    if ($words[0] != "") {
        // Increment data_count.
        $data_count++;
        $output .= "<p>WORD COUNT: " . implode(", ", getData("word_count", $wordquery)) . "</p>\n";
    }

    $output .= "</div>";
    if ($data_count > 0) {
        echo $output;
    }

    $tagsquery = "SELECT detailed_tag FROM story_tags WHERE id = '$id' AND (tag_type='author' OR tag_type='type')";
    $tags = getData("detailed_tag", $tagsquery);
    if (!empty($tags)) {
        echo "<div class='tags'>";
        foreach ($tags as $tag) {
            echo "<p class='tag'>" . $tag . "</p>";
        }
        echo "</div>";
    }
}


function addChildrenNew($id, $lang, $v, $collection_bool)
{
    include("db_connect.php");

    if ($id === "0") {
        $sql = "SELECT story_metadata.id AS cid, title, snippet, chronology, content_version FROM story_metadata JOIN story_content ON story_metadata.id = story_content.id WHERE story_metadata.id NOT IN (SELECT child_id FROM story_reference_web) ORDER BY chronology, title ASC";
    } else {
        if ($collection_bool == false) {
            $sql = "SELECT child_id AS cid, child_version AS content_version, title, snippet, chronology FROM story_reference_web JOIN (story_metadata JOIN story_content ON story_metadata.id = story_content.id) ON story_reference_web.child_id = story_metadata.id AND story_content.content_version=story_reference_web.child_version WHERE story_reference_web.parent_id = \"$id\" AND story_content.content_language=\"$lang\" AND story_reference_web.child_id NOT IN (SELECT DISTINCT id FROM story_tags WHERE tag='collection') ORDER BY IFNULL(chronology, (SELECT chronology FROM story_reference_web JOIN story_metadata ON story_reference_web.child_id = story_metadata.id WHERE story_reference_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
            // Need to make this so it gets the child version right.
        } else {
            $sql = "SELECT child_id AS cid, title, snippet, chronology, content_version FROM story_reference_web JOIN (story_metadata JOIN story_content ON story_metadata.id = story_content.id) ON story_reference_web.child_id = story_metadata.id WHERE story_reference_web.parent_id = \"$id\" AND story_content.content_version=$v AND story_content.content_language=\"$lang\" AND story_reference_web.child_id IN (SELECT DISTINCT id FROM story_tags WHERE tag='collection') ORDER BY IFNULL(chronology, (SELECT chronology FROM story_reference_web JOIN story_metadata ON story_reference_web.child_id = story_metadata.id WHERE story_reference_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
        }
    }
    // The above is... messy. But it works. The IFNULL needs to be replaced with proper recursion and a MIN.

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);

    // If the content doesn't have any children (chapter, etc.), this function will return nothing, and no children will be displayed to the user.
    if ($num_rows != 0) {
        echo "<section class='structure'>";

        // WHAT THE FUCK HAPPENED HERE?!
        $uniquea = [];
        // This loop echoes the individual children.
        while ($row = $result->fetch_assoc()) {
            $uniqueid = $row["cid"];
            $uniquev = $row["content_version"];

            if (in_array($uniqueid, $uniquea)) {
                continue;
            } else {
                echo "<div class='padding'><button id='card$uniqueid' class='contentsButton' onclick='goTo(\"" . $uniqueid . "." . $uniquev . "\")'>";
                $image = getImages($uniqueid, $uniquev, $lang, $row["title"]);
                if ($image != "") {
                    echo $image;
                }
                $snippet = (string) $row["snippet"];
                echo "<div class='contentButtonText'><p>" . $row["title"] . "</p><p>" . $snippet . "</p>";
                getDetails($uniqueid, $uniquev, $lang);
                echo "</div></button></div>";
            }
            array_push($uniquea, $uniqueid);
        }
        echo "</section>";
    }
}


// This function finds any and all children that a given piece of content has, then echoes them in a list format.
function addChildren($id, $lang, $v)
{
    include("db_connect.php");

    $sql_child_count = "SELECT COUNT(child_id) AS id_count FROM story_reference_web WHERE parent_id='$id' AND parent_version='$v'";
    $child_count = getData("id_count", $sql_child_count);
    if ($child_count[0] > 0) {
        $sql_grandchild_count = "SELECT COUNT(child_id) AS grandchild_count FROM story_reference_web WHERE parent_id IN (SELECT child_id FROM story_reference_web WHERE parent_id='$id' AND parent_version='$v')";
        $grandchild_count = getData("grandchild_count", $sql_grandchild_count);
        if ($grandchild_count[0] == 0) {
            echo "<nav><button class='standaloneButton' onclick='readAsStandalone()'>Read as Standalone</button></nav>";
        }
    }

    if ($id == "0") {
        $sql_collection_count = "SELECT COUNT(id) as id_count FROM story_metadata WHERE id NOT IN (SELECT child_id FROM story_reference_web) AND id IN (SELECT id FROM story_tags WHERE tag='collection')";
        $sql_content_count = "SELECT COUNT(id) as id_count FROM story_metadata WHERE id NOT IN (SELECT child_id FROM story_reference_web) AND id NOT IN (SELECT id FROM story_tags WHERE tag='collection')";
    } else {
        $sql_collection_count = "SELECT COUNT(child_id) AS id_count FROM story_reference_web WHERE parent_id='$id' AND parent_version='$v' AND child_id IN (SELECT DISTINCT id FROM story_tags WHERE tag='collection')";
        $sql_content_count = "SELECT COUNT(child_id) AS id_count FROM story_reference_web WHERE parent_id='$id' AND parent_version='$v' AND child_id NOT IN (SELECT DISTINCT id FROM story_tags WHERE tag='collection')";
    }

    $collection_count = getData("id_count", $sql_collection_count);
    $content_count = getData("id_count", $sql_content_count);

    if ($collection_count[0] > 0 && $content_count[0] > 0) {
        addChildrenNew($id, $lang, $v, false);
        echo "<h2>Collections</h2>";
        addChildrenNew($id, $lang, $v, true);
    } else if ($collection_count[0] > 0 && $content_count[0] == 0) {
        addChildrenNew($id, $lang, $v, true);
    } else if ($collection_count[0] == 0 && $content_count[0] > 0) {
        addChildrenNew($id, $lang, $v, false);
    }
}


function getUserLanguage()
{
    if (isset($_COOKIE["languagePreference"])) {
        return $_COOKIE["languagePreference"];
    } else {
        $locale = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
        if ($locale != null) {
            return substr($locale, 0, 2);
        } else {
            return "en";
        }
    }
}


function populateStaticGenerator($base_path, $lang)
{
    $path = getcwd();

    if ($base_path != "") {
        $path .= "/" . "static/" . $base_path . "/" . $lang . ".html";
    } else {
        $path .= "/" . "static/" . $lang . ".html";
    }

    return $path;
}


function populateStatic($base_path)
{
    $lang = getUserLanguage();
    $path = populateStaticGenerator($base_path, $lang);

    if (file_exists($path)) {
        echo file_get_contents($path);
    } else {
        $default_path = populateStaticGenerator($base_path, "en");
        if (file_exists($default_path)) {
            echo file_get_contents($default_path);
        } else {
            echo "<p>Missing file at " . $path . " or " . $default_path . ". Please report to admin@wallofhistory.com</p>";
        }
    }
}


function getLeaves($id)
{
    include("db_connect.php");

    if ($id == "0") {
        $all_leaves_query = "SELECT DISTINCT story_reference_web.child_id FROM story_reference_web JOIN story_metadata ON story_reference_web.child_id=story_metadata.id WHERE story_reference_web.child_id NOT IN (SELECT DISTINCT parent_id FROM story_reference_web) ORDER BY story_metadata.chronology ASC";
        $result_all_leaves = $mysqli->query($all_leaves_query);

        $all_leaves = array();
        if (!is_bool($result_all_leaves)) {
            while ($row_all_leaves = $result_all_leaves->fetch_assoc()) {
                array_push($all_leaves, $row_all_leaves["child_id"]);
            }
        }

        return "'" . implode('\', \'', $all_leaves) . "'";
    } else {
        // Get full list of all leaf nodes in the tree.
        // Note that the chronology value is necessary to ensure that the ultimate output is ordered "depth blind" — i.e., leaves at a depth of one will not float to the top, ahead of leaves at a depth of two with a lower chronology.
        $all_leaves_query = "SELECT DISTINCT story_reference_web.child_id FROM story_reference_web JOIN story_metadata ON story_reference_web.child_id=story_metadata.id WHERE story_reference_web.child_id NOT IN (SELECT DISTINCT parent_id FROM story_reference_web) ORDER BY story_metadata.chronology ASC";
        $result_all_leaves = $mysqli->query($all_leaves_query);

        $all_leaves = array();
        if (!is_bool($result_all_leaves)) {
            while ($row_all_leaves = $result_all_leaves->fetch_assoc()) {
                array_push($all_leaves, $row_all_leaves["child_id"]);
            }
        }

        $nodes = array("\"" . $id . "\"");
        $leaves = array();
        $descendant_leaves = getChildren($nodes, $leaves, $all_leaves);
        // array_intersect() is necessary for aforementioned sorting by chronology.
        return "'" . implode('\', \'', array_intersect($all_leaves, $descendant_leaves)) . "'";
    }
}


// Recursive function to get all children of a given node, separating out the leaves.
function getChildren($nodes, $leaves, $all_leaves)
{
    include("db_connect.php");

    $imploded_nodes = implode(",", $nodes);
    $sql_children = "SELECT child_id, child_version FROM story_reference_web WHERE parent_id IN ($imploded_nodes) AND child_id NOT IN (SELECT id FROM story_tags WHERE tag='collection')";
    $result_children = $mysqli->query($sql_children);

    $nodes = array();
    while ($row_children = $result_children->fetch_assoc()) {
        $child_id = $row_children["child_id"];
        if (in_array($child_id, $all_leaves) && !in_array($child_id, $leaves)) {
            array_push($leaves, $child_id);
        } else if (!in_array($child_id, $all_leaves)) {
            array_push($nodes, "\"" . $child_id . "\"");
        }
    }

    if (count($nodes) == 0) {
        return $leaves;
    } else {
        return getChildren($nodes, $leaves, $all_leaves);
    }
}


?>