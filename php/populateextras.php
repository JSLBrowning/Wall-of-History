<?php

/**
 * Filename: populateExtras.php
 * Author: James Browning (JSLBrowning)
 * Function: This file contains functions used to populate content pages with userspace metadata, such as descriptions, release dates, and alternate versions.
 */
date_default_timezone_set('America/New_York');


/********************
 * HELPER FUNCTIONS *
 ********************/


// Function to wrap a detail string in a <span.detail> tag.
function detailWrapper($detail, $label = null)
{
    if (!is_null($label)) {
        return "<span class='detail'><p>" . $label . ":</p><p>" . $detail . "</p></span>\n";
    } else {
        return "<span class='detail'><p>" . $detail . "</p></span>\n";
    }
}


/**********************
 * SETTINGS FUNCTIONS *
 **********************/


function getSettings($id, $lang, $v)
{
    include("db_connect.php");

    $successes = 0;

    // VERSIONS
    $sql = "SELECT DISTINCT content_version FROM shin_content WHERE content_id='$id' ORDER BY content_version";
    // Perfom selection.
    $result = $mysqli->query($sql);

    if ($result->num_rows > 1) {
        echo "<form>";
        echo "<select onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            // https://stackoverflow.com/questions/7562095/redirect-on-select-option-in-select-box
            $newv = $row["content_version"];

            $sqlnext = "SELECT version_title FROM shin_content WHERE content_id=\"$id\" AND content_version=\"$newv\" AND content_language='en' LIMIT 1";
            // IFNULL(SELECT content_language FROM shin_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

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
        $successes++;
    }

    // LANGUAGES
    // Create selection statement and perfom selection.
    $sql = "SELECT DISTINCT content_language FROM shin_content WHERE content_id=\"$id\" AND content_version=\"$v\" ORDER BY content_language";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 1) {
        if ($successes == 0) {
            echo "<form>";
        }

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
        $successes++;
    }

    // EQUIVALENTS
    $sql_eq = "SELECT right_id AS alt_id, right_version AS alt_v FROM shin_equivalents WHERE left_id=\"$id\" AND left_version=\"$v\" UNION ALL SELECT left_id AS alt_id, left_version AS alt_v FROM shin_equivalents WHERE right_id=\"$id\" AND right_version=\"$v\"";
    $result_eq = $mysqli->query($sql_eq);

    if ($result_eq->num_rows > 0) {
        if ($successes == 0) {
            echo "<form>";
        }

        echo "<select id=\"equivalentSelect\" onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        echo "<option selected=\"true\" disabled=\"disabled\">Equivalent stories…</option>";
        while ($row = $result_eq->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            // https://stackoverflow.com/questions/7562095/redirect-on-select-option-in-select-box
            $newv = $row["alt_v"];
            $newid = $row["alt_id"];

            $sqlnext = "SELECT title FROM shin_content WHERE id=\"$newid\" AND content_version=\"$newv\" LIMIT 1";
            // IFNULL(SELECT content_language FROM shin_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

            $resultnext = $mysqli->query($sqlnext);
            while ($rownext = $resultnext->fetch_assoc()) {
                echo "<option value=\"" . $newid . "." . $newv . "\">" . $rownext["title"] . "</option>";
            }
        }
        echo "</select>";
        $successes++;
    }

    if ($successes != 0) {
        echo "</form>";
        echo "<hr>";
    }
}


function getSettingsReference($id, $v, $lang)
{
    include("db_connect.php");

    $successes = 0;

    // VERSIONS
    $sql = "SELECT DISTINCT content_version FROM reference_content WHERE entry_id=\"$id\" ORDER BY content_version";
    // Perfom selection.
    $result = $mysqli->query($sql);

    if ($result->num_rows > 1) {
        echo "<fieldset>";
        echo "<select onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            // https://stackoverflow.com/questions/7562095/redirect-on-select-option-in-select-box
            $newv = $row["content_version"];

            $sqlnext = "SELECT version_title FROM referemce_content WHERE entry_id=\"$id\" AND content_version=\"$newv\" AND content_language=\"en\" LIMIT 1";
            // IFNULL(SELECT content_language FROM shin_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

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
        $successes++;
    }

    // LANGUAGES
    // Create selection statement and perfom selection.
    $sql = "SELECT DISTINCT content_language FROM reference_content WHERE entry_id=\"$id\" AND content_version=\"$v\" ORDER BY content_language";
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
        $successes++;
    }

    if ($successes != 0) {
        echo "</form>";
        echo "<hr>";
    }
}


/********************
 * DETAIL FUNCTIONS *
 ********************/


function getAdaptedFrom($id)
{
    $successes = 0;

    $originals_query = "SELECT original_id FROM shin_adaptations WHERE adaptation_id = '$id'";
    $originals = getData("original_id", $originals_query);
    if (!empty($originals)) {
        $title_query = "SELECT content_title FROM shin_content WHERE content_id = '" . $originals[0] . "' AND content_version = 1 LIMIT 1";
        $title = getData("content_title", $title_query);

        echo "<span class='detail'><p>Adapted from:</p><p><a onclick=\"goTo('" . $originals[0] . "')\">" . $title[0] . "</a></p></span>\n";
        $successes++;
    }

    return $successes;
}


function getAdaptedInto($id)
{
    $successes = 0;

    $adaptations_query = "SELECT adaptation_id FROM shin_adaptations WHERE original_id = '$id'";
    $adaptations = getData("adaptation_id", $adaptations_query);
    if (!empty($adaptations)) {
        $title_query = "SELECT content_title FROM shin_content WHERE content_id = '" . $adaptations[0] . "' AND content_version = 1 LIMIT 1";
        $title = getData("content_title", $title_query);

        echo "<span class='detail'><p>Adapted into:</p><p><a onclick=\"goTo('" . $adaptations[0] . "')\">" . $title[0] . "</a></p></span>\n";
        $successes++;
    }

    return $successes;
}


function getWordCount($id, $version)
{
    include("db_connect.php");
    $words = 0;

    // Get word count of self.
    $query = "";
    if ($version == null) {
        $query = "SELECT content_words FROM shin_content WHERE content_id='$id' AND content_version=(SELECT MIN(content_version) FROM shin_content WHERE content_id='$id') LIMIT 1";
    } else {
        $query = "SELECT content_words FROM shin_content WHERE content_id='$id' AND content_version=$version LIMIT 1";
    }
    $result = $mysqli->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row["content_words"])) {
                $words = $words + $row["content_words"];
            }
        }
    }

    // Get word count of any children.
    $child_query = "SELECT child_id FROM shin_web WHERE parent_id='$id' AND (parent_version=$version OR parent_version IS NULL)";
    $children = $mysqli->query($child_query);
    if ($children != null) {
        while ($child = $children->fetch_assoc()) {
            $child_id = $child["child_id"];
            $child_words = getWordCount($child_id, null);
            if ($child_words != null) {
                $words = $words + $child_words;
            }
        }
    }

    return $words;
}


// Function to get details for content and display them.
function getDetailsAside($id, $v, $lang)
{
    // Initialize variable to count number of details.
    $details = 0;

    // Get snippet, unless same as subtitle.
    $snippet_query = "SELECT content_snippet FROM shin_content WHERE content_id='$id' AND (content_version='$v' OR content_version IS NULL) AND (content_language='$lang' OR content_language IS NULL) /* AND content_snippet NOT IN (SELECT content_subtitle FROM shin_content WHERE content_id='$id' AND content_version='$v' AND content_language='$lang') */";
    $snippet = getData("content_snippet", $snippet_query);
    if (!empty($snippet)) {
        echo detailWrapper($snippet[0]);
        $details++;
    }

    // Get adapted from.
    $details = $details + getAdaptedFrom($id);

    // Get release date.
    $release_query = "SELECT release_date FROM shin_metadata WHERE content_id='$id' AND content_version='$v' AND content_language='$lang'";
    $release = getData("release_date", $release_query);
    if (!empty($release)) {
        $release_date = date('F jS, Y', strtotime($release[0]));
        echo detailWrapper($release_date, "Release Date");
        $details++;
    }

    // Get adapted into.
    $details = $details + getAdaptedInto($id);

    // Get word count.
    $words = getWordCount($id, $v);
    if ($words != null && $words != 0) {
        echo detailWrapper(number_format($words), "Word Count");
        $details++;
    }
    // NEED TO GET WORD COUNT OF CHILDREN.

    // Get completion status.
    $completion_query = "SELECT completion_status FROM shin_metadata WHERE content_id='$id' AND content_version=$v AND content_language='$lang'";
    $completion = getData("completion_status", $completion_query);
    // If not NULL...
    if (!empty($completion)) {
        $details++;
        switch ($completion[0]) {
            case 0:
                echo detailWrapper("Not Started");
                break;
            case 1:
                echo detailWrapper("In Progress");
                break;
            case 2:
                echo detailWrapper("Complete");
                break;
            default:
                echo detailWrapper("Cancelled");
                break;
        }
    }
}


/**********************
 * DOWNLOAD FUNCTIONS *
 **********************/


function getDownloads($id, $v = null, $lang = null)
{
    // If v or lang are null, list all options, then identify them through text.
    // Otherwise, just identify file type.

    $config = getJSONConfigVariables();
    $paths = translateToPath($config, $id, $v, $lang);
    $icons = [
        "zip" => "<i class='fa-solid fa-file-zipper fa-lg'></i>",
        "pdf" => "<i class='fa-solid fa-file-pdf fa-lg'></i>",
        "docx" => "<i class='fa-solid fa-file-word fa-lg'></i>",
        "epub" => "<i class='fa-solid fa-tablet-screen-button fa-lg'></i>"
    ];

    $allFiles = [];
    // Find any files with the .pdf, .docx, .epub, or .zip extension.
    foreach ($paths as $path) {
        $foundFiles = glob($_SERVER['DOCUMENT_ROOT'] . $path . "*.{pdf,docx,epub,zip}", GLOB_BRACE);
        if (!empty($foundFiles)) {
            // Combine the arrays.
            $allFiles = array_merge($allFiles, $foundFiles);
        }
    }

    if (!empty($allFiles)) {
        echo "<hr>";
    }

    // Create an <a> for each file.
    foreach ($allFiles as $file) {
        // Get the file extension.
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $icon = $icons[$ext];
        // Get the file name.
        // $name = basename($file, "." . $ext);

        // Create the <a> tag.
        echo "<a class='anchor__button' href='$file' download>$icon " . strtoupper($ext) . "</a>\n";
    }
}


/**********************
 * POPULATE FUNCTIONS *
 **********************/


function populateAside($id, $lang, $v)
{
    include("db_connect.php");

    if (strpos($_SERVER["REQUEST_URI"], "read") !== false) {
        // 1. Echo version selectors.
        // 2. If any of above are NOT NULL, echo <hr>.
        getSettings($id, $lang, $v);
        // 3. Echo details (snippet, release date, et cetera).
        // 4. If any of above are NOT NULL, echo <hr>.
        getDetailsAside($id, $v, $lang);
        // 5. Echo downloads.
        echo "<div class='extra__areas'>";
        getDownloads($id, $v, $lang);
        echo "</div>";
    } else if (strpos($_SERVER["REQUEST_URI"], "reference") !== false) {
        // 1. Echo details (snippet [.snippet], release date [if any], word count [if any]).
        // 1.i. If any of above are NOT NULL, echo <hr>.
        // getDetailsAsideReference($id, $v, $lang);
        // 2. Echo version selectors.
        // 2.i. If any of above are NOT NULL, echo <hr>.
        getSettingsReference($id, $v, $lang);
    }
}
