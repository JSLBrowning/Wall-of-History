<?php

    // Each ID should only be fetched once, with the title being that of the language of the... ya know, closest to the top of the list. And... version 1.
    // This function generates the list of contents seen on the settings page. Once that's done, it's up to the JavaScript to give that list functionality.
    include("db_connect.php");
    echo "<h1>Settings</h1>";

    // First, find all the unique tags with which we select items, and make the selector.
    $sql_tags = "SELECT DISTINCT(tag) AS tag, tag_type, detailed_tag FROM story_tags WHERE (tag_type = 'type' OR tag_type = 'language' OR tag_type = 'author') ORDER BY tag_type DESC, detailed_tag ASC";
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

    $sql = "SELECT DISTINCT child_id AS cid, title, chronology FROM story_reference_web JOIN (story_metadata JOIN story_content ON story_metadata.id = story_content.id) ON story_reference_web.child_id = story_metadata.id WHERE story_content.content_version=1 AND child_id NOT IN (SELECT DISTINCT parent_id FROM story_reference_web) AND story_content.content_language=\"en\" ORDER BY IFNULL(chronology, (SELECT chronology FROM story_reference_web JOIN story_metadata ON story_reference_web.child_id = story_metadata.id WHERE story_reference_web.parent_id = cid ORDER BY chronology LIMIT 1)) ASC, title ASC";
    $result = $mysqli->query($sql);

    echo "<ol id='sortable' class='ui-sortable' style='list-stype-type: none;'>\n";

    while ($row = $result->fetch_assoc()) {
        echo "          <li class='ui-sortable-handle'>\n";
        $cid = $row["cid"];

        $sql_nexttags = "SELECT GROUP_CONCAT(tag SEPARATOR ', ') AS tags FROM story_tags WHERE (tag_type = 'type' OR tag_type = 'language' OR tag_type = 'author') AND id = '$cid' LIMIT 1";
        $result_nexttags = $mysqli->query($sql_nexttags);
        $itemtags = "";
        while ($row_nexttags = $result_nexttags->fetch_assoc()) {
            $itemtags = $itemtags . $row_nexttags["tags"];
        }

        // If an item has a vague title, such as “Chapter 1,” the name of the parent item needs to be appended to the front end.
        $sql_chapter = "SELECT tag FROM story_tags WHERE (id = '$cid' AND tag = 'chapter')";

        $result_chapter = $mysqli->query($sql_chapter);
        $num_chap = mysqli_num_rows($result_chapter);
        if ($num_chap == 0) {
            echo "<input data-tags='" . $itemtags . "' type='checkbox' name='" . $row["cid"] . ".1' id='" . $row["cid"] . ".1' value='" . $row["cid"] . ".1'>\n";
            echo "<label for='" . $row["cid"] . ".1'>⇵ " . $row["title"] . " <a href='/read/?id=" . $row["cid"] . "&v=1'><span class='linkarrow'></span></a></label>\n";
        } else {
            $sql_title = "SELECT title FROM story_content JOIN story_reference_web ON story_reference_web.parent_id = story_content.id WHERE story_reference_web.child_id = '" . $row["cid"] . "' LIMIT 1";

            $result_title = $mysqli->query($sql_title);
            while ($row_title = $result_title->fetch_assoc()) {
                echo "<input data-tags='" . $itemtags . "' type='checkbox' name='" . $row["cid"] . ".1' id='" . $row["cid"] . ".1' value='" . $row["cid"] . ".1'>\n";
                echo "<label for='" . $row["cid"] . ".1'>⇵ " . $row_title["title"] . ": " . $row["title"] . " <a href='/read/?id=" . $row["cid"] . "&v=1'><span class='linkarrow'></span></a></label>\n";
            }
        }
        echo "</li>\n";
    }
    echo "</ol>";
