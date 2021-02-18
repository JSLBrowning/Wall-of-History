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
function populateHead($id)
{
    // This function is pretty straightforward — it populates the head of the page with content-specific OGP data.
    include("..//php/db_connect.php");

    // Idea: Recurse UP web to get OGP image, recurse DOWN to get chronology for table of contents.
    // https://www.mysqltutorial.org/mysql-recursive-cte/

    // Another idea: make the names of CSS files match their associated type tags or pieces of content, then have them automatically be loaded.
    // Load CSS files of parents first, then self, so self always takes precedence.

    $sql = "SELECT title, snippet, large_image FROM woh_metadata WHERE woh_metadata.id = \"" . $id . "\"";
    // IFNULL(large_image, (SELECT large_image FROM woh_web JOIN woh_metadata ON woh_web.parent_id = woh_metadata.id WHERE woh_web.child_id = \"" . $id . "\" LIMIT 1))
    // The above doesn't work for some reason, even though it's repurposed from the chronology recursion below.
    // Need to work on it.

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ((!($id == 0)) && ($num_rows == 0)) {
        echo "<meta http-equiv=\"Refresh\" content=\"0; url='https://wallofhistory.com/404/'\" />";
    }
    while ($row = $result->fetch_assoc()) {
        if (is_null($row["large_image"])) {
            echo "<meta content='" . strip_tags($row["title"]) . " | Wall of History' property='og:title'/>
                    <meta content='" . $row["snippet"] . " | Wall of History' property='og:description'/>
                    <meta content='http://www.wallofhistory.co/img/ogp.png' property='og:image'/>
                    <meta content='summary_large_image' name='twitter:card'/>
                    <meta content='@Wall_of_History' name='twitter:site'/>
                    <title>" . strip_tags($row["title"]) . " | Wall of History</title>";
        } else {
            echo "<meta content='" . strip_tags($row["title"]) . " | Wall of History' property='og:title'/>
                    <meta content='" . $row["snippet"] . " | Wall of History' property='og:description'/>
                    <meta content='http://www.wallofhistory.co/img/ogp.png' property='og:image'/>
                    <meta content='" . $row["large_image"] . "' property='og:image'/>
                    <meta content='summary_large_image' name='twitter:card'/>
                    <meta content='@Wall_of_History' name='twitter:site'/>
                    <title>" . strip_tags($row["title"]) . " | Wall of History</title>";
        }
    }
}

function addCSS($id)
{
    include("..//php/db_connect.php");
    $sql = "SELECT tag FROM woh_tags WHERE id = \"" . $id . "\" AND tag_type = 'type'";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<link rel='stylesheet' type='text/css' href='/css/type/" . $row["tag"] . ".css'>\n";
    }
    echo "    <link rel='stylesheet' type='text/css' href='/css/id/" . $id . ".css'>";
}

function hasChildren($id)
{
    // This function finds any and all children that a given piece of content has, then echoes them in a list format.
    include("..//php/db_connect.php");

    $sql = "SELECT child_id AS cid, title, snippet, small_image, large_image, chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = \"" . $id . "\" ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
    /* Okay, it works, but it's not elegant — the downward recursion (@ IFNULL) for the chronology values only works for one level. Should try to replace that with true recursion. */

    // If the user visits the read page without specifying an ID, the page will display the top of the table of contents.
    if ($id == "0") {
        $sql = "SELECT id AS cid, title, snippet, small_image, large_image, chronology FROM woh_metadata WHERE id NOT IN (SELECT child_id FROM woh_web) ORDER BY IFNULL(chronology, (SELECT MIN(chronology) FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid AND chronology IS NOT NULL)), title ASC";
        echo "<h1>Table of Contents</h1>";
    }
    // The above is... messy. But it works. The IFNULL needs to be replaced with proper recursion and a MIN.

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);

    // If the content doesn't have any children (chapter, etc.), this function will return nothing, and no children will be displayed to the user.
    if ($num_rows != 0) {
        // The loop below checks if the content in question is one work composed of several chapters, and if it is, displays the "read as standalone" button.
        if ($id != "0") {
            $sql_standalone = "SELECT child_id FROM woh_web JOIN woh_tags ON woh_web.child_id=woh_tags.id WHERE woh_web.parent_id = '$id' AND woh_tags.tag ='chapter'";
            $result_standalone = $mysqli->query($sql_standalone);

            if (mysqli_num_rows($result_standalone) != 0) {
                echo "<nav><button class='standaloneButton' onclick='readStandalone()'>Read as Standalone</button></nav>";
            }
        }

        // This loop echoes the individual children.
        while ($row = $result->fetch_assoc()) {
            echo "<button class='contentsButton' onclick='window.location.href=\"/read/?id=" . $row["cid"] . "\";'>";
            if (($row["small_image"] === NULL) && ($row["large_image"] === NULL)) {
            } elseif (!($row["small_image"] === NULL)) {
                echo "<div class='contentsImg'><img src='" . $row["small_image"] . "'></div>";
            } else {
                echo "<div class='contentsImg'><img src='" . $row["large_image"] . "'></div>";
            }
            $snippet = (string) $row["snippet"];
            if (strlen($snippet) > 196) {
                echo "<div class='contentsText'><p>" . $row["title"] . "</p><p>" . substr($snippet, 0, 196) . "…</p></div></button>";
            } else {
                echo "<div class='contentsText'><p>" . $row["title"] . "</p><p>" . $snippet . "</p></div></button>";
            }
        }
    }
}

function loadHeader($id)
{
    include("..//php/db_connect.php");
    $sql_header = "SELECT html FROM woh_content JOIN woh_headers ON woh_content.header = woh_headers.header_id WHERE woh_content.id = '$id' LIMIT 1";
    // Make this recurse up to get parents if none.
    $result_header = $mysqli->query($sql_header);
    $num_rows = mysqli_num_rows($result_header);

    if ($num_rows == 0) {
        echo "<img src=\"/img/Faber-Files-Bionicle-logo-Transparent.png\" alt=\"BIONICLE\" height=\"80\" width=\"405\" style=\"cursor: pointer;\" onclick=\"window.location.href='/'\">";
    } else {
        while ($row_header = $result_header->fetch_assoc()) {
            echo $row_header["html"];
        }
    }
}

function loadContent($id)
{
    // This function is the most complicated, echoing the actuals contents of the page from the top (parent(s), title, author) down (content).
    include("..//php/db_connect.php");

    // GET PARENT(S), IF ANY, AND DISPLAY AT THE TOP OF <MAIN>
    $sql = "SELECT * FROM woh_web WHERE child_id = \"" . $id . "\"";

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ((!($id == 0)) && ($num_rows == 0)) {
        // For some reason, this isn't working on Chapter 4: The Mask of Light, even though nothing in the web lists it as a child. Need to figure out why.
        echo "<h2><a onClick='location.href=\"/read/\"'>Table of Contents</a></h2>";
    } else {
        while ($row = $result->fetch_assoc()) {
            if ($num_rows == 1) {
                $sql_title = "SELECT title FROM woh_metadata WHERE id = \"" . $row["parent_id"] . "\"";
                $result_title = $mysqli->query($sql_title);
                while ($row_title = $result_title->fetch_assoc()) {
                    echo "<h2><a onClick='location.href=\"/read/?id=" . $row["parent_id"] . "\"'>" . $row_title["title"] . "</a></h2>";
                }
            } else {
                echo "<h2>↑</h2>";
            }
        }
    }

    // GET AND DISPLAY TITLE
    $sql = "SELECT title FROM woh_metadata WHERE id = \"" . $id . "\"";

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<h1>" . $row["title"] . "</h1>";
    }

    // GET AND DISPLAY CONTRIBUTORS
    $sql = "SELECT tag FROM woh_tags WHERE id = \"" . $id . "\" AND (tag_type = 'developer' OR tag_type = 'author' OR tag_type = 'illustrator')";

    $result = $mysqli->query($sql);
    $num_rows = mysqli_num_rows($result);
    if ($num_rows == 0) {
    } elseif ($num_rows == 1) {
        while ($row = $result->fetch_assoc()) {
            echo "<h2>" . $row["tag"] . "</h2>";
        }
    } else {
        echo "<h2>";
        $num_commas = $num_rows - 1;
        while ($row = $result->fetch_assoc()) {
            echo $row["tag"];
            if ($num_commas > 0) {
                echo ", ";
                $num_commas--;
            }
        }
        echo "</h2>";
    }

    // GET AND DISPLAY CONTENT
    $sql = "SELECT main FROM woh_content WHERE id = \"" . $id . "\"";

    // Display snippet in place of main if main empty (for parent works)?

    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        // Self-explanatory — the main column contains the contents of the main tag.
        /* echo str_replace("<p>", "<a></a><p>", (string)$row["main"]); */
        // Okay, we'll come back to page numbers later.
        echo $row["main"];
    }
    hasChildren($id);
}

function populateSettings()
{
    // This function generates the list of contents seen on the settings page. Once that's done, it's up to the JavaScript to give that list functionality.
    include("..//php/db_connect.php");

    // First, find all the unique tags with which we select items, and make the selector.
    $sql_tags = "SELECT DISTINCT(tag) AS tag, detailed_tag FROM woh_tags WHERE (tag_type = 'type' OR tag_type = 'language' OR tag_type = 'organizational' OR tag_type = 'author') ORDER BY tag_type";
    $result_tags = $mysqli->query($sql_tags);

    echo "<form action='#'><fieldset><label for='check'>Check all…</label><select name='check' id='check' onchange = 'checkAll(this);' onfocus='this.selectedIndex = -1;'>";
    while ($row_tags = $result_tags->fetch_assoc()) {
        echo "<option value='" . $row_tags["tag"] . "'>'" . $row_tags["detailed_tag"] . "'</option>";
    }
    echo "</select></form>";

    echo "<label for='uncheck'>Uncheck all…</label><select name='uncheck' id='uncheck' onchange = 'uncheckAll(this);' onfocus='this.selectedIndex = -1;'>";
    $result_tags->data_seek(0);
    while ($row_tags = $result_tags->fetch_assoc()) {
        echo "<option value='" . $row_tags["tag"] . "'>'" . $row_tags["tag"] . "'</option>";
    }
    echo "</select></fieldset>";

    $sql = "SELECT child_id AS cid, title, chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE child_id NOT IN (SELECT parent_id FROM woh_web) ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)) ASC, title ASC";
    $result = $mysqli->query($sql);

    echo "<ol id='sortable' class='ui-sortable' style='list-stype-type: none;'>\n";

    while ($row = $result->fetch_assoc()) {
        echo "          <li class='ui-sortable-handle'>\n";

        // If an item has a vague title, such as “Chapter 1,” the name of the parent item needs to be appended to the front end.
        $sql_chapter = "SELECT tag FROM woh_tags WHERE (id = '" . $row["cid"] . "' AND tag = 'chapter')";

        $result_chapter = $mysqli->query($sql_chapter);
        $num_chap = mysqli_num_rows($result_chapter);
        if ($num_chap == 0) {
            $sql_nexttags = "SELECT GROUP_CONCAT(tag SEPARATOR ', ') AS tags FROM woh_tags WHERE (tag_type = 'type' OR tag_type = 'language' OR tag_type = 'organizational' OR tag_type = 'author') AND id = '" . $row["cid"] . "'";
            $result_nexttags = $mysqli->query($sql_nexttags);
            while ($row_nexttags = $result_nexttags->fetch_assoc()) {
                $itemtags = $row_nexttags["tags"];
            }
            echo "              <input data-tags='" . $itemtags . "' type='checkbox' name='" . $row["cid"] . "' id='" . $row["cid"] . "' value='" . $row["cid"] . "'>\n";
            echo "              <label for='" . $row["cid"] . "'>" . $row["title"] . "<a href='/read/?id=" . $row["cid"] . "'>↗</a></label>\n";
        } else {
            $sql_title = "SELECT title FROM woh_metadata JOIN woh_web ON woh_web.parent_id = woh_metadata.id WHERE woh_web.child_id = '" . $row["cid"] . "'";

            $result_title = $mysqli->query($sql_title);
            while ($row_title = $result_title->fetch_assoc()) {
                echo "              <input data-type='game' data-author='GregFarshtey' type='checkbox' name='" . $row["cid"] . "' id='" . $row["cid"] . "' value='" . $row["cid"] . "'>\n";
                echo "              <label for='" . $row["cid"] . "'>" . $row_title["title"] . ": " . $row["title"] . "<a href='/read/?id=" . $row["cid"] . "'>↗</a></label>\n";
            }
        }
        echo "          </li>\n";
    }
    echo "</ol>";
}
