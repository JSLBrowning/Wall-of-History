<?php


/***********************
 * UNIVERSAL FUNCTIONS *
 ***********************/


// This function takes the color palette cookie and returns the appropriate CSS class, inserting it into the <html> tag, ideally before the page even finishes loading for the end user.
function chooseColors()
{
    if (!isset($_COOKIE["colorPreference"])) {
        echo "<html lang='en " . $_COOKIE["colorPreference"] . "'>";
    } else {
        echo "<html lang='en'>";
    }
}


// This function returns an array of results when a query returns just one row.
// Can be used for simplifying functions that require several simple queries.
function getData($column, $query)
{
    include("..//php/db_connect.php");

    $data = [];
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row[$column]);
        }
    }
    return $data;
}


/***************************
 * END UNIVERSAL FUNCTIONS *
 ***************************/


/*******************************
 * .STORY POPULATION FUNCTIONS *
 * These functions populate the
 * reader page, from top (head)
 * to bottom (main).
 *******************************/


// This function populates the <head> of the page with content-specific OGP data.
function populateHead($id, $lang, $v)
{
    include("..//php/db_connect.php");

    $result = $mysqli->query("SELECT title, snippet, large_image FROM woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id WHERE woh_metadata.id = \"$id\" AND woh_content.content_version = \"$v\" AND woh_content.content_language = \"$lang\"");
    $num_rows = mysqli_num_rows($result);
    $image_options = array("/img/ogp.png");

    // If query returns no result, determine whether or not user is on the table of contents, and respond accordingly.
    if ((!($id == 0)) && ($num_rows == 0)) {
        echo "<meta content='404 | Wall of History' property='og:title'/>\n
            <meta content='Six heroes. One destiny.' property='og:description'/>\n
            <meta content='http://www.wallofhistory.com" . $image_options[0] . "' property='og:image'/>\n
            <meta content='summary_large_image' name='twitter:card'/>\n
            <meta content='@Wall_of_History' name='twitter:site'/>\n
            <title>404 | Wall of History</title>\n";
        echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://wallofhistory.com/404/'\"/>\n";
    } else if ($id == 0 && $num_rows == 0) {
        echo "<meta content='Table of Contents | Wall of History' property='og:title'/>\n
            <meta content='Six heroes. One destiny.' property='og:description'/>\n
            <meta content='http://www.wallofhistory.com" . $image_options[0] . "' property='og:image'/>\n
            <meta content='summary_large_image' name='twitter:card'/>\n
            <meta content='@Wall_of_History' name='twitter:site'/>\n
            <title>Table of Contents | Wall of History</title>\n";
    }

    // If query does return a result, respond accordingly.
    while ($row = $result->fetch_assoc()) {
        $title = strip_tags($row["title"]);

        // Get full title, if chapter.
        $chapter_query = "SELECT COUNT(tag) AS tags FROM woh_tags WHERE id = '$id' AND tag = 'chapter'";
        $chapter_count = getData("tags", $chapter_query);
        if ($chapter_count[0] > 0) {
            $parent_query = "SELECT title FROM woh_content JOIN woh_web ON woh_web.parent_id = woh_content.id WHERE woh_web.child_id = '" . $id . "' LIMIT 1";
            $parent = strip_tags(getData("title", $parent_query)[0]);
            $title = $parent . ": " . $title;
        }

        // Get lower-priority image, if available.
        if (!is_null($row["large_image"])) {
            array_unshift($image_options, $row["large_image"]);
        }

        // Get higher-priority images, if available.
        $possible_locations = array("/img/ogp/" . $id . ".png", "/img/ogp" . $id . "_" . $lang . ".png", "/img/ogp" . $id . "_" . $v . ".png", "/img/ogp" . $id . "_" . $v . "_" . $lang . ".png");
        foreach ($possible_locations as $location) {
            if (file_exists(".." . $location)) {
                array_unshift($image_options, $location);
            }
        }

        echo "<meta content='" . $title . " | Wall of History' property='og:title'/>\n
            <meta content='" . $row["snippet"] . "' property='og:description'/>\n
            <meta content='http://www.wallofhistory.com" . $image_options[0] . "' property='og:image'/>\n
            <meta content='summary_large_image' name='twitter:card'/>\n
            <meta content='@Wall_of_History' name='twitter:site'/>\n
            <title>" . $title . " | Wall of History</title>\n";
    }
}


// This function populates the head of the page with content-specific CSS links.
function populateCSS($id)
{
    // Type
    include("..//php/db_connect.php");
    $sql = "SELECT tag FROM woh_tags WHERE id = \"" . $id . "\" AND tag_type = 'type'";
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
    $sql = "SELECT parent_id FROM woh_web WHERE child_id IN (SELECT parent_id FROM woh_web WHERE child_id='" . $id . "');";
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
    $sql = "SELECT parent_id FROM woh_web WHERE child_id ='" . $id . "';";
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
    include("..//php/db_connect.php");
    $sql_header = "SELECT html FROM woh_content JOIN woh_headers ON woh_content.header = woh_headers.header_id WHERE woh_content.id = '$id' LIMIT 1";
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
    include("..//php/db_connect.php");

    $parents_query = "SELECT * FROM woh_web WHERE child_id=\"$id\" AND child_version=$v";
    $parents = $mysqli->query($parents_query);
    $num_parents = mysqli_num_rows($parents);

    if ($id === "0") {
        echo "<div class='titleBoxText'><h1>Table of Contents</h1></div></section>";
    } else if ((!($id === "0")) && ($num_parents === 0)) {
        echo "<div class='titleBoxText'><h3><a onClick='location.href=\"/read/\"'>BIONICLE</a></h3>";
    } else {
        // Get and display image, if any.
        if (file_exists("../img/story/contents/" . $id . ".png")) {
            echo "<img src='/img/story/contents/" . $id . ".png' alt='" . $title[0] . "'>\n";
        }
        echo "<div class='titleBoxText'>";

        if ($num_parents === 1) {
            while ($row = $parents->fetch_assoc()) {
                $parent_title_query = "SELECT title FROM woh_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
                $parent_title = $mysqli->query($parent_title_query);
                while ($new_row = $parent_title->fetch_assoc()) {
                    echo "<h3><a onClick=\"goTo('" . $row["parent_id"] . "." . $row["parent_version"] . "')\">" . $new_row["title"] . "</a></h3>";
                }
            }
        } else if ($num_parents > 1) {
            echo "<div class='multiparents'><button onclick='carouselBack(this)'>⮜</button>";
            while ($row = $parents->fetch_assoc()) {
                $sql_title = "SELECT title FROM woh_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
                // ORDER BY chronology, title ASC
                $result_title = $mysqli->query($sql_title);
                while ($row_title = $result_title->fetch_assoc()) {
                    $parentid = $row["parent_id"];
                    echo "<h3><a id='$parentid' onClick=\"goTo('$parentid." . $row["parent_version"] . "')\">" . $row_title["title"] . "</a></h3>";
                }
            }
            echo "<button onclick='carouselForward(this)'>⮞</button></div>";
        }
    }
}


function sanitizeContributors($contributors_array)
{
    $exploded_contributors = array();
    foreach ($contributors_array as $contributor) {
        array_push($exploded_contributors, explode(",", $contributor));
    }

    $contributor_types = array_column($exploded_contributors, 0);
    $unique_contributor_types = array_unique($contributor_types);
}


function loadContentContributors($id)
{
    include("..//php/db_connect.php");

    $contributors_query = "SELECT detailed_tag AS tag FROM woh_tags WHERE id = \"" . $id . "\" AND (tag_type = 'author')";
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
        echo implode(", ", $contributors_array) . "</h3>";
    }
}


// This function loads the content for the .story section of a page.
function loadContent($id, $v, $lang)
{
    include("..//php/db_connect.php");

    // Determine if this content is divided into pages, and respond accordingly.
    $pages_query = "SELECT COUNT(tag) AS tag_count FROM woh_tags WHERE id='$id' AND tag='pages'";
    if (getData("tag_count", $pages_query)[0] > 0) {
        echo "<section class='story pages'><section class='titleBox'>\n";
    } else {
        echo "<section class='story'><section class='titleBox'>\n";
    }

    // Get title (to be displayed later.)
    $title_query = "SELECT title FROM woh_content WHERE id = \"" . $id . "\" AND content_language = \"" . $lang . "\" AND content_version = \"" . $v . "\" LIMIT 1";
    $title = getData("title", $title_query);

    loadContentParents($id, $v, $title);

    // Display title.
    if (!($id === "0") && ($title[0] !== "")) {
        echo "<h1>" . $title[0] . "</h1>";
    }

    // Get and display subtitle, if any.
    $subtitle_query = "SELECT subtitle FROM woh_content WHERE id = \"" . $id . "\" AND content_version = \"" . $v . "\" AND content_language = \"" . $lang . "\"";
    $subtitle = getData("subtitle", $subtitle_query);
    if (!($id === "0") && ($subtitle[0] != "")) {
        echo "<h2>" . $subtitle[0] . "</h2>";
    }

    loadContentContributors($id);
    echo "</section>";

    // Get and display content.
    $sql = "SELECT main FROM woh_content WHERE id=\"$id\" AND content_version=\"$v\" AND content_language=\"$lang\"";

    // Display snippet in place of main if main empty (for parent works)?

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row["main"];
    }

    echo "</section>";
}



function getDetails($id, $primeversion, $lang)
{
    $versionquery = "SELECT DISTINCT version_title FROM woh_content WHERE id = '$id' AND content_language = '$lang' ORDER BY content_version ASC LIMIT 3";
    echo "<p>" . implode(", ", getData("version_title", $versionquery)) . "</p>\n";

    $releasequery = "SELECT publication_date FROM woh_metadata WHERE id = '$id'";
    echo "<p>RELEASED " . str_replace("-", "/", implode(", ", getData("publication_date", $releasequery))) . "</p>\n";

    $wordquery = "SELECT ROUND(AVG(word_count), 0) AS word_count FROM woh_content WHERE id = '$id' and content_version = '$primeversion'";
    echo "<p>WORD COUNT: " . implode(", ", getData("word_count", $wordquery)) . "</p>\n";
}


function addChildrenNew($id, $lang, $v, $collection_bool)
{
    include("..//php/db_connect.php");

    if ($id === "0") {
        $sql = "SELECT woh_metadata.id AS cid, title, snippet, small_image, large_image, chronology, content_version FROM woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id WHERE woh_metadata.id NOT IN (SELECT child_id FROM woh_web) ORDER BY chronology, title ASC";
    } else {
        if ($collection_bool == false) {
            $sql = "SELECT child_id AS cid, title, snippet, small_image, large_image, chronology, content_version FROM woh_web JOIN (woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id) ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = \"$id\" AND woh_content.content_version=$v AND woh_content.content_language=\"$lang\" AND woh_web.child_id NOT IN (SELECT DISTINCT id FROM woh_tags WHERE tag='collection') ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
            /* Okay, it works, but it's not elegant — the downward recursion (@ IFNULL) for the chronology values only works for one level. Should try to replace that with true recursion. */
            /* Also, woh_content.content_version=1 isn't right, it needs to match the web. */
        } else {
            $sql = "SELECT child_id AS cid, title, snippet, small_image, large_image, chronology, content_version FROM woh_web JOIN (woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id) ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = \"$id\" AND woh_content.content_version=$v AND woh_content.content_language=\"$lang\" AND woh_web.child_id IN (SELECT DISTINCT id FROM woh_tags WHERE tag='collection') ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
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

            if (in_array($uniqueid, $uniquea)) {
                continue;
            } else {
                echo "<div class='padding'><button id='card$uniqueid' class='contentsButton' onclick='goTo(\"" . $uniqueid . "." . $row["content_version"] . "\")'>";
                if (file_exists("../img/story/contents/" . $uniqueid . ".png")) {
                    echo "<img src='/img/story/contents/" . $uniqueid . ".png' alt='" . $row["title"] . "'>";
                } else if ($row["small_image"] != NULL) {
                    echo "<img src='" . $row["small_image"] . "'>";
                }
                $snippet = (string) $row["snippet"];
                echo "<div class='contentButtonText'><p>" . $row["title"] . "</p><p>" . $snippet . "</p><div class='versions'>";
                getDetails($uniqueid, $row["content_version"], $lang);
                echo "</div></div></button></div>";
            }
            array_push($uniquea, $uniqueid);
        }
        echo "</section>";
    }
}


// This function finds any and all children that a given piece of content has, then echoes them in a list format.
function addChildren($id, $lang, $v)
{
    include("..//php/db_connect.php");

    $sql_child_count = "SELECT COUNT(child_id) AS id_count FROM woh_web WHERE parent_id='$id' AND parent_version='$v'";
    $child_count = getData("id_count", $sql_child_count);
    if ($child_count[0] > 0) {
        $sql_grandchild_count = "SELECT COUNT(child_id) AS grandchild_count FROM woh_web WHERE parent_id IN (SELECT child_id FROM woh_web WHERE parent_id='$id' AND parent_version='$v')";
        $grandchild_count = getData("grandchild_count", $sql_grandchild_count);
        if ($grandchild_count[0] == 0) {
            echo "<nav><button class='standaloneButton' onclick='readAsStandalone()'>Read as Standalone</button></nav>";
        }
    }

    if ($id == "0") {
        $sql_collection_count = "SELECT COUNT(id) as id_count FROM woh_metadata WHERE id NOT IN (SELECT child_id FROM woh_web) AND id IN (SELECT id FROM woh_tags WHERE tag='collection')";
        $sql_content_count = "SELECT COUNT(id) as id_count FROM woh_metadata WHERE id NOT IN (SELECT child_id FROM woh_web) AND id NOT IN (SELECT id FROM woh_tags WHERE tag='collection')";
    } else {
        $sql_collection_count = "SELECT COUNT(child_id) AS id_count FROM woh_web WHERE parent_id='$id' AND parent_version='$v' AND child_id IN (SELECT DISTINCT id FROM woh_tags WHERE tag='collection')";
        $sql_content_count = "SELECT COUNT(child_id) AS id_count FROM woh_web WHERE parent_id='$id' AND parent_version='$v' AND child_id NOT IN (SELECT DISTINCT id FROM woh_tags WHERE tag='collection')";
    }

    $collection_count = getData("id_count", $sql_collection_count);
    $content_count = getData("id_count", $sql_content_count);

    if ($collection_count[0] > 0 && $content_count[0] > 0) {
        echo "<h2>Collections</h2>";
        // https://www.mysqltutorial.org/mysql-recursive-cte/
        // https://stackoverflow.com/questions/50405786/get-leaf-nodes-for-a-specific-tree-in-mysql
        // https://stackoverflow.com/questions/20215744/how-to-create-a-mysql-hierarchical-recursive-query
        addChildrenNew($id, $lang, $v, true);
        echo "<h2>Contents</h2>";
        addChildrenNew($id, $lang, $v, false);
    } else if ($collection_count[0] > 0 && $content_count[0] == 0) {
        addChildrenNew($id, $lang, $v, true);
    } else if ($collection_count[0] == 0 && $content_count[0] > 0) {
        addChildrenNew($id, $lang, $v, false);
    }
}



function populateSettings()
{
    // Each ID should only be fetched once, with the title being that of the language of the... ya know, closest to the top of the list. And... version 1.
    // This function generates the list of contents seen on the settings page. Once that's done, it's up to the JavaScript to give that list functionality.
    include("..//php/db_connect.php");

    // First, find all the unique tags with which we select items, and make the selector.
    $sql_tags = "SELECT DISTINCT(tag) AS tag, tag_type, detailed_tag FROM woh_tags WHERE (tag_type = 'type' OR tag_type = 'language' OR tag_type = 'author') ORDER BY tag_type DESC, detailed_tag ASC";
    $result_tags = $mysqli->query($sql_tags);

    echo "<form action='#'><fieldset><label for='check'>Check all…</label><select name='check' id='check' onchange = 'checkAll(this);' onfocus='this.selectedIndex = 0;'>";
    echo "<option selected='true' disabled='disabled'>Select an option…</option>";
    echo "<option value='everything'>Everything</option>";
    while ($row_tags = $result_tags->fetch_assoc()) {
        echo "<option value='" . $row_tags["tag"] . "'>" . $row_tags["detailed_tag"] . "</option>";
    }
    echo "</select></form>";

    echo "<label for='uncheck'>Uncheck all…</label><select name='uncheck' id='uncheck' onchange = 'uncheckAll(this);' onfocus='this.selectedIndex = 0;'>";
    $result_tags->data_seek(0);
    echo "<option selected='true' disabled='disabled'>Select an option…</option>";
    echo "<option value='everything'>Everything</option>";
    while ($row_tags = $result_tags->fetch_assoc()) {
        echo "<option value='" . $row_tags["tag"] . "'>" . $row_tags["detailed_tag"] . "</option>";
    }
    echo "</select>
    <label for='lang'>Language…</label>
    <select name=\"lang\" id=\"lang\" onchange=\"localStorage.setItem('languagePreference', this.value)\" onfocus=\"this.selectedIndex = 0;\">
    <option selected='true' disabled='disabled'>Select an option…</option>
    <option value=\"en\">English</option>
    <option value=\"es\">Español</option>
    </select>
    </fieldset>";
    // onclick localStorage.setItem('languagePreference', 'es');

    echo "<button id='resetButton' onclick='resetReader()'>Reset to Default</button>";

    $sql = "SELECT DISTINCT child_id AS cid, title, chronology FROM woh_web JOIN (woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id) ON woh_web.child_id = woh_metadata.id WHERE woh_content.content_version=1 AND child_id NOT IN (SELECT DISTINCT parent_id FROM woh_web) AND woh_content.content_language=\"en\" ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)) ASC, title ASC";
    $result = $mysqli->query($sql);

    echo "<ol id='sortable' class='ui-sortable' style='list-stype-type: none;'>\n";

    while ($row = $result->fetch_assoc()) {
        echo "          <li class='ui-sortable-handle'>\n";
        $cid = $row["cid"];

        $sql_nexttags = "SELECT GROUP_CONCAT(tag SEPARATOR ', ') AS tags FROM woh_tags WHERE (tag_type = 'type' OR tag_type = 'language' OR tag_type = 'author') AND id = '$cid' LIMIT 1";
        $result_nexttags = $mysqli->query($sql_nexttags);
        $itemtags = "";
        while ($row_nexttags = $result_nexttags->fetch_assoc()) {
            $itemtags = $itemtags . $row_nexttags["tags"];
        }

        // If an item has a vague title, such as “Chapter 1,” the name of the parent item needs to be appended to the front end.
        $sql_chapter = "SELECT tag FROM woh_tags WHERE (id = '$cid' AND tag = 'chapter')";

        $result_chapter = $mysqli->query($sql_chapter);
        $num_chap = mysqli_num_rows($result_chapter);
        if ($num_chap == 0) {
            echo "<input data-tags='" . $itemtags . "' type='checkbox' name='" . $row["cid"] . ".1' id='" . $row["cid"] . ".1' value='" . $row["cid"] . ".1'>\n";
            echo "<label for='" . $row["cid"] . ".1'>⇵ " . $row["title"] . " <a href='/read/?id=" . $row["cid"] . "&v=1'>🢅</a></label>\n";
        } else {
            $sql_title = "SELECT title FROM woh_content JOIN woh_web ON woh_web.parent_id = woh_content.id WHERE woh_web.child_id = '" . $row["cid"] . "' LIMIT 1";

            $result_title = $mysqli->query($sql_title);
            while ($row_title = $result_title->fetch_assoc()) {
                echo "<input data-tags='" . $itemtags . "' type='checkbox' name='" . $row["cid"] . ".1' id='" . $row["cid"] . ".1' value='" . $row["cid"] . ".1'>\n";
                echo "<label for='" . $row["cid"] . ".1'>⇵ " . $row_title["title"] . ": " . $row["title"] . " <a href='/read/?id=" . $row["cid"] . "&v=1'>🢅</a></label>\n";
            }
        }
        echo "</li>\n";
    }
    echo "</ol>";
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
        $path .= "\static\\" . $base_path . "\\" . $lang . ".html";
    } else {
        $path .= "\static\\" . $lang . ".html";
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
    include("..//php/db_connect.php");

    if ($id == "0") {
        $all_leaves_query = "SELECT DISTINCT woh_web.child_id FROM woh_web JOIN woh_metadata ON woh_web.child_id=woh_metadata.id WHERE woh_web.child_id NOT IN (SELECT DISTINCT parent_id FROM woh_web) ORDER BY woh_metadata.chronology ASC";
        $result_all_leaves = $mysqli->query($all_leaves_query);

        $all_leaves = array();
        while ($row_all_leaves = $result_all_leaves->fetch_assoc()) {
            array_push($all_leaves, $row_all_leaves["child_id"]);
        }
        
        return "'" . implode('\', \'', $all_leaves) . "'";
    } else {
        // Get full list of all leaf nodes in the tree.
        // Note that the chronology value is necessary to ensure that the ultimate output is ordered "depth blind" — i.e., leaves at a depth of one will not float to the top, ahead of leaves at a depth of two with a lower chronology.
        $all_leaves_query = "SELECT DISTINCT woh_web.child_id FROM woh_web JOIN woh_metadata ON woh_web.child_id=woh_metadata.id WHERE woh_web.child_id NOT IN (SELECT DISTINCT parent_id FROM woh_web) ORDER BY woh_metadata.chronology ASC";
        $result_all_leaves = $mysqli->query($all_leaves_query);

        $all_leaves = array();
        while ($row_all_leaves = $result_all_leaves->fetch_assoc()) {
            array_push($all_leaves, $row_all_leaves["child_id"]);
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
    include("..//php/db_connect.php");

    $imploded_nodes = implode(",", $nodes);
    $sql_children = "SELECT child_id, child_version FROM woh_web WHERE parent_id IN ($imploded_nodes) AND child_id NOT IN (SELECT id FROM woh_tags WHERE tag='collection')";
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
