<?php
function populateSettingsModal($id, $v, $lang)
{
    include("..//php/db_connect.php");

    // VERSIONS
    echo "<h2>Versions</h2>";
    // Create selection statement.
    $sql = "SELECT DISTINCT content_version FROM woh_content WHERE id=\"$id\" ORDER BY content_version";
    // Perfom selection.
    $result = $mysqli->query($sql);

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

    // LANGUAGE
    echo "<hr>\n<h2>Languages</h2>";
    // Create selection statement and perfom selection.
    $sql = "SELECT DISTINCT content_language FROM woh_content WHERE id=\"$id\" AND content_version=\"$v\" ORDER BY content_language";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 1) {
        echo "<fieldset>";
        echo "<select onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            $newlang = strtoupper($row["content_language"]);
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

    // COMPARISON
    echo "<hr>\n<h2>Comparison</h2>";
    // Create selection statement and perform selection.
    // Note that this should only display versions for comparison when the language of the alternate matches the language of the current — you should not, for example, be able to compare Hapka’s English version of “The Legend of Mata Nui” with Farshtey’s Spanish version.
    $sql = "SELECT DISTINCT content_version FROM woh_content WHERE id=\"$id\" AND content_language=\"$lang\" AND content_version !=\"$v\" ORDER BY content_version";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo "<fieldset>";
        echo "<select onchange=\"compare(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            $newv = $row["content_version"];
            $sqlnext = "SELECT version_title FROM woh_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"en\" LIMIT 1";
            // IFNULL(SELECT content_language FROM woh_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

            $resultnext = $mysqli->query($sqlnext);
            echo "<option selected='selected' disabled hidden>Select an option…</option>";
            while ($rownext = $resultnext->fetch_assoc()) {
                echo "<option value=\"" . $newv . "\">" . $rownext["version_title"] . "</option>";
            }
        }
        echo "</select>";
        echo "</fieldset>";

        // Dismissal button.
        echo "<button onclick=\"dismissComparison()\">Dismiss Comparison</button>";
    }
}
