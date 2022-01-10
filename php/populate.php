<?php
// TO-DO: Add a populator for the settings button based on available sections.
/* STEP 1: GET ACTIVE LANG SECTION, GET ALL VERSION SUBSECTIONS, IF ANY.
        (IF NONE OF EITHER, BUTTON STAYS HIDDEN -- IF EITHER, BLOCK.)
        STEP 2: IF CHANGE VERSION, REALLOCATE DISPLAY='NONE' TO OTHER VERSION.
        IF CHANGE LANGUAGE, SHOW FIRST VERSION IN LIST, REPOPULATE VERSION LIST.
        DO THIS IN JS? */
// ALSO add a read as standalone button generator and create corresponding JS.
/* STEP 1: GENERATE TEMP READING ORDER BASED ON CHILDREN: XHTML CALL TO PHP FUNCTION IN JS, TRIGGERED BY PHP-GENERATED BUTTON.
        JUMP TO FIRST CHAP.
        IF ID IN TEMP READING ORDER, TAKE TEMP ORDER AS PRECEDENT.
        IF EXIT PAGE OR RELOAD TO PAGE OUTSIDE READING ORDER, DELETE TEMP ORDER.
        IF LAST CHAP, DELETE TEMP ORDER.
        IF SAVE ON TEMP CHAPTER... PRESERVE TEMP ORDER UNTIL NEXT RELOAD. */

// This function populates the head of the page with content-specific OGP data.
function populateHead($id, $lang, $v)
{
    include("..//php/db_connect.php");

    // Idea: Recurse UP web to get OGP image, recurse DOWN to get chronology for table of contents.
    // https://www.mysqltutorial.org/mysql-recursive-cte/

    $sql = "SELECT title, snippet, large_image FROM woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id WHERE woh_metadata.id = \"$id\" AND woh_content.content_version = \"$v\" AND woh_content.content_language = \"$lang\"";
    // IFNULL(large_image, (SELECT large_image FROM woh_web JOIN woh_metadata ON woh_web.parent_id = woh_metadata.id WHERE woh_web.child_id = \"" . $id . "\" LIMIT 1))
    // The above doesn't work for some reason, even though it's repurposed from the chronology recursion below.
    // Need to work on it.

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ((!($id == 0)) && ($num_rows == 0)) {
        echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://wallofhistory.com/404/'\"/>\n";
    }
    while ($row = $result->fetch_assoc()) {
        $title = strip_tags($row["title"]);
        if (is_null($row["large_image"])) {
            echo "<meta content='" . $title . " | Wall of History' property='og:title'/>\n
            <meta content='" . $row["snippet"] . " | Wall of History' property='og:description'/>\n
            <meta content='http://www.wallofhistory.com/img/ogp.png' property='og:image'/>\n
            <meta content='summary_large_image' name='twitter:card'/>\n
            <meta content='@Wall_of_History' name='twitter:site'/>\n
            <title>" . $title . " | Wall of History</title>\n";
        } else {
            echo "<meta content='" . $title . " | Wall of History' property='og:title'/>\n
            <meta content='" . $row["snippet"] . " | Wall of History' property='og:description'/>\n
            <meta content='" . $row["large_image"] . "' property='og:image'/>\n
            <meta content='summary_large_image' name='twitter:card'/>\n
            <meta content='@Wall_of_History' name='twitter:site'/>\n
            <title>" . $title . " | Wall of History</title>\n";
        }

        // GET PARENT TITLE.
        // THIS SUCKS REPLACE IT.
        $sqltitle = "SELECT * FROM woh_tags WHERE id = '$id' AND tag = 'chapter'";
        $resulttitle = $mysqli->query($sqltitle);
        $num_rows = mysqli_num_rows($resulttitle);
        if ($num_rows > 0) {
            $sqlparent = "SELECT title FROM woh_content JOIN woh_web ON woh_web.parent_id = woh_content.id WHERE woh_web.child_id = '" . $id . "' LIMIT 1";
            $resultparent = $mysqli->query($sqlparent);
            while ($rowparent = $resultparent->fetch_assoc()) {
                $parenttitle = strip_tags($rowparent['title']);
                echo "<meta content='" . $parenttitle . ":" . $title . " | Wall of History' property='og:title'/>\n
                <title>" . $parenttitle . ":" . $title . " | Wall of History</title>\n";
            }
        }
    }
}

// This function populates the head of the page with content-specific CSS links.
function addCSS($id)
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
    OR maybe... if there's a conflict and not enough info to solve it, get all chronology values for conflicting CSS values, average them together, and pick whichever one is closest to the current chronology.
    CHRONOLOGY ALGORITHM FOR GRANDPARENTS, STACK HISTORY FOR PARENTS. */
    // Grandparent
    $sql = "SELECT parent_id FROM woh_web WHERE child_id IN (SELECT parent_id FROM woh_web WHERE child_id='" . $id . "');";
    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 1) {
        //
    } else if ($num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            if (file_exists("..//css/id/" . $row["parent_id"] . ".css")) {
                echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $row["parent_id"] . ".css'>\n";
            }
        }
    }

    // Parent
    $sql = "SELECT parent_id FROM woh_web WHERE child_id ='" . $id . "';";
    $result = $mysqli->query($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            if (file_exists("../css/id/" . $row["parent_id"] . ".css")) {
                echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $row["parent_id"] . ".css'>\n";
            }
        }
    }

    // Self
    if (file_exists("../css/id/" . $id . ".css")) {
        echo "<link rel='stylesheet' type='text/css' href='/css/id/" . $id . ".css'>\n";
    }

    // Overrides
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

// This function finds any and all children that a given piece of content has, then echoes them in a list format.
function addChildren($id, $lang, $v)
{
    include("..//php/db_connect.php");

    if ($id === "0") {
        $sql = "SELECT woh_metadata.id AS cid, title, snippet, small_image, large_image, chronology, content_version FROM woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id WHERE woh_metadata.id NOT IN (SELECT child_id FROM woh_web) ORDER BY chronology, title ASC";
    } else {
        $sql = "SELECT child_id AS cid, title, snippet, small_image, large_image, chronology, content_version FROM woh_web JOIN (woh_metadata JOIN woh_content ON woh_metadata.id = woh_content.id) ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = \"$id\" AND woh_content.content_version=$v AND woh_content.content_language=\"$lang\" ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
        /* Okay, it works, but it's not elegant — the downward recursion (@ IFNULL) for the chronology values only works for one level. Should try to replace that with true recursion. */
        /* Also, woh_content.content_version=1 isn't right, it needs to match the web. */
    }

    // If the user visits the read page without specifying an ID, the page will display the top of the table of contents.
    if ($id == "0") {
        echo "<h1>Table of Contents</h1>";
    }
    // The above is... messy. But it works. The IFNULL needs to be replaced with proper recursion and a MIN.

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);

    // If the content doesn't have any children (chapter, etc.), this function will return nothing, and no children will be displayed to the user.
    if ($num_rows != 0) {
        // The loop below checks if the content in question is one work composed of several chapters, and if it is, displays the "read as standalone" button.
        if ($id != "0") {
            $sql_standalone = "SELECT child_id FROM woh_web";
            $result_standalone = $mysqli->query($sql_standalone);

            if (mysqli_num_rows($result_standalone) != 0) {
                echo "<nav><button class='standaloneButton' onclick='readAsStandalone()'>Read as Standalone</button></nav>";
            }
        }

        // WHAT THE FUCK HAPPENED HERE?!
        $uniquea = [];
        // This loop echoes the individual children.
        while ($row = $result->fetch_assoc()) {
            $uniqueid = $row["cid"];

            if (in_array($uniqueid, $uniquea)) {
                continue;
            } else {
                echo "<button class='contentsButton' onclick='goTo(\"" . $uniqueid . "." . $row["content_version"] . "\")'>";
                if ($row["small_image"] != NULL) {
                    echo "<div class='contentsImg'><img src='" . $row["small_image"] . "'></div>";
                }
                $snippet = (string) $row["snippet"];
                if (strlen($snippet) > 196) {
                    echo "<div class='contentsText'><p>" . $row["title"] . "</p><p>" . substr($snippet, 0, 196) . "…</p></div></button>";
                } else {
                    echo "<div class='contentsText'><p>" . $row["title"] . "</p><p>" . $snippet . "</p></div></button>";
                }
            }

            array_push($uniquea, $uniqueid);
        }
    }
}

function loadContent($id, $lang, $v)
{
    // This function is the most complicated, echoing the actuals contents of the page from the top (parent(s), title, author) down (content).
    include("..//php/db_connect.php");

    // GET PARENT(S), IF ANY, AND DISPLAY AT THE TOP OF <MAIN>
    $sql = "SELECT * FROM woh_web WHERE child_id=\"$id\" AND child_version=$v";

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ((!($id == "0")) && ($num_rows == 0)) {
        // For some reason, this isn't working on Chapter 4: The Mask of Light, even though nothing in the web lists it as a child. Need to figure out why.
        echo "<h3><a onClick='location.href=\"/read/\"'>Table of Contents</a></h3>";
    } else if ($num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            $sql_title = "SELECT title FROM woh_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
            $result_title = $mysqli->query($sql_title);
            while ($row_title = $result_title->fetch_assoc()) {
                echo "<h3><a onClick=\"goTo('" . $row["parent_id"] . "." . $row["parent_version"] . "')\">" . $row_title["title"] . "</a></h3>";
            }
        }
    } else {
        echo "<div class='multiparents'><button onclick='carouselBack(this)'>⮜</button>";
        while ($row = $result->fetch_assoc()) {
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

    // GET AND DISPLAY IMAGE
    /* $sql = "SELECT large_image FROM woh_content WHERE id = \"" . $id . "\" AND content_language = \"" . $lang . "\" AND content_version = \"" . $v . "\"";

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<img src=\"" . $row["large_image"] . "\">";
    }
    */

    // GET AND DISPLAY TITLE
    $sql = "SELECT title FROM woh_content WHERE id = \"" . $id . "\" AND content_language = \"" . $lang . "\" AND content_version = \"" . $v . "\"";

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<h1>" . $row["title"] . "</h1>";
    }

    // GET AND DISPLAY SUBTITLE
    $sql = "SELECT subtitle FROM woh_content WHERE id = \"" . $id . "\" AND content_language = \"" . $lang . "\" AND content_version = \"" . $v . "\"";

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $subtitle = $row["subtitle"];
        if ($subtitle != NULL) {
            echo "<h2>" . $subtitle . "</h2>";
        }
    }

    // GET AND DISPLAY CONTRIBUTORS
    $sql = "SELECT detailed_tag AS tag FROM woh_tags WHERE id = \"" . $id . "\" AND (tag_type = 'author')";

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            echo "<h3>" . $row["tag"] . "</h3>";
        }
    }
    if ($num_rows > 1) {
        echo "<h3>";
        $num_commas = $num_rows - 1;
        while ($row = $result->fetch_assoc()) {
            echo $row["tag"];
            if ($num_commas > 0) {
                echo ", ";
                $num_commas--;
            }
        }
        echo "</h3>";
    }

    // Get and display content.
    $sql = "SELECT main FROM woh_content WHERE id=\"$id\" AND content_version=\"$v\" AND content_language=\"$lang\"";

    // Display snippet in place of main if main empty (for parent works)?

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        // Self-explanatory — the main column contains the contents of the main tag.
        /* echo str_replace("<p>", "<a></a><p>", (string)$row["main"]); */
        // Okay, we'll come back to page numbers later.
        echo $row["main"];
    }
    addChildren($id, $lang, $v);
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
