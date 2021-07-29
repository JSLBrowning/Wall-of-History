<?php
function populateSettingsModal($id, $v, $lang)
{
    // Do I still NEED this?
    include("..//php/db_connect.php");

    // Create selection statement.
    $sql = "SELECT DISTINCT content_version FROM woh_content WHERE id=\"$id\" ORDER BY content_version";
    // Perfom selection.
    $result = $mysqli->query($sql);

    echo "<h2>Versions</h2>";
    if ($result->num_rows > 1) {
        echo "<fieldset>";
        echo "<select onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            // https://stackoverflow.com/questions/7562095/redirect-on-select-option-in-select-box
            $newv = $row["content_version"];

            $sqlnext = "SELECT version_title FROM woh_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"en\" LIMIT 1";
            // IFNULL(SELECT content_language FROM woh_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

            $resultnext = $mysqli->query($sqlnext);
            while ($rownext = $resultnext->fetch_assoc()) {
                if ($newv == $v) {
                    echo "<option value=\"" . $id . "." . $newv . "\" selected>" . $rownext["version_title"] . "</option>";
                } else {
                    echo "<option value=\"" . $id . "." . $newv . "\">" . $rownext["version_title"] . "</option>";
                }
            }
        }
        echo "</select>";
        echo "</fieldset>";
    } else {
        echo "<p style=\"text-align:center;\">This is the only version available.</p>";
    }
    echo "<hr>\n<h2>Languages</h2>";

    // Create selection statement.
    $sql = "SELECT DISTINCT content_language FROM woh_content WHERE id=\"$id\" AND content_version=\"$v\" ORDER BY content_language";
    // Perfom selection.
    $result = $mysqli->query($sql);

    if ($result->num_rows > 1) {
        echo "<fieldset>";
        echo "<select onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            $newlang = $row["content_language"];
            if ($newlang == $lang) {
                echo "<option value=\"" . $id . "." . $v . "." . $newlang . "\" selected><strong>" . $newlang . "</strong></option>";
            } else {
                echo "<option value=\"" . $id . "." . $v . "." . $newlang . "\"><strong>" . $newlang . "</strong></option>";
            }
        }
        echo "</select>";
        echo "</fieldset>";
    } else {
        echo "<p style=\"text-align:center;\">This is the only language available.</p>";
    }
}
