<?php
date_default_timezone_set('America/New_York');

// Generic query function.
function getDataAside($column, $query)
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


function getAdaptedFrom($id) {
    $successes = 0;

    $originals_query = "SELECT original_id FROM story_adaptations WHERE adaptation_id = '$id'";
    $originals = getDataAside("original_id", $originals_query);
    if (!empty($originals)) {
        $title_query = "SELECT title FROM story_content WHERE id = '" . $originals[0] . "' AND content_version = 1 LIMIT 1";
        $title = getDataAside("title", $title_query);

        echo "<p>Adapted from <a onclick=\"goTo('" . $originals[0] . "')\">" . $title[0] . "</a>.</p>\n";
        $successes++;
    }

    return $successes;
}


function getAdaptedInto($id) {
    $successes = 0;

    $adaptations_query = "SELECT adaptation_id FROM story_adaptations WHERE original_id = '$id'";
    $adaptations = getDataAside("adaptation_id", $adaptations_query);
    if (!empty($adaptations)) {
        $title_query = "SELECT title FROM story_content WHERE id = '" . $adaptations[0] . "' AND content_version = 1 LIMIT 1";
        $title = getDataAside("title", $title_query);

        echo "<p>Adapted into <a onclick=\"goTo('" . $adaptations[0] . "')\">" . $title[0] . "</a>.</p>\n";
        $successes++;
    }

    return $successes;
}


function getDetailsAside($id, $lang, $v) {
    include("db_connect.php");

    $successes = 0;

    // if != subtitle
    $snippet_query = "SELECT snippet FROM story_content WHERE id='$id' AND content_version='$v' AND content_language='$lang'";
    $snippet = getDataAside("snippet", $snippet_query);
    if (!empty($snippet)) {
        echo "<p class='snippet'>" . $snippet[0] . "</p>\n";
        $successes++;
    }

    $successes = $successes + getAdaptedFrom($id);

    $release_query = "SELECT publication_date FROM story_metadata WHERE id='$id'";
    $release = getDataAside("publication_date", $release_query);
    if (!empty($release)) {
        if ($release[0] != "") {
            echo "<p>Released on " . date('F jS, Y', strtotime($release[0])) . ".</p>\n";
            $successes++;
        }
    }

    $successes = $successes + getAdaptedInto($id);

    $words_query = "SELECT word_count FROM story_content WHERE id='$id' AND content_version='$v' AND content_language='$lang'";
    $words = getDataAside("word_count", $words_query);
    if (isset($words[0])) {
        echo "<p>Word Count: " . number_format($words[0]) . "</p>\n";
        $successes++;
    }/* else {
        $children = getLeaves($id);
        // Check Stack Overflow to see if anyone's answered that question.
        $words_query_sum = "SELECT SUM(word_count) AS word_count FROM story_content WHERE id IN ($children) AND content_version=1 AND content_language='$lang'";
        $words_sum = getDataAside("word_count", $words_query_sum);
        if (!is_null($words_sum[0])) {
            echo "<p>Word Count: " . number_format($words_sum[0]) . "</p>\n";
            $successes++;
        }
    } */

    if ($successes != 0) {
        echo "<hr>\n";
    }
}


// Function to wrap a detail string in a <span.detail> tag.
function detailWrapper($detail, $label=null) {
    if (!is_null($label)) {
        return "<span class='detail'><p>" . $label . ":</p><p>" . $detail . "</p></span>\n";
    } else {
        return "<span class='detail'><p>" . $detail . "</p></span>\n";
    }
}


// Function to get details for content and display them.
function getDetails($id, $v, $lang) {
    // Get snippet.
    $snippet_query = "SELECT content_snippet FROM shin_content WHERE content_id='$id' AND content_version='$v' AND content_language='$lang'";
    $snippet = getDataAside("content_snippet", $snippet_query);
    if (!empty($snippet)) {
        echo detailWrapper($snippet[0]);
    }

    // Get release date.
    $release_query = "SELECT release_date FROM shin_metadata WHERE content_id='$id' AND content_version='$v' AND content_language='$lang'";
    $release = getDataAside("release_date", $release_query);
    if (!empty($release)) {
        $release_date = date('F jS, Y', strtotime($release[0]));
        echo detailWrapper($release_date, "Release Date");
    }

    // Get word count.
    $words_query = "SELECT content_words FROM shin_content WHERE content_id='$id' AND content_version='$v' AND content_language='$lang'";
    $words = getDataAside("content_words", $words_query);
    if (!empty($words)) {
        echo detailWrapper(number_format($words[0]), "Word Count");
    }
    // NEED TO GET WORD COUNT OF CHILDREN.

    // Get completion status.
    $completion_query = "SELECT completion_status FROM shin_metadata WHERE content_id='$id' AND content_version='$v' AND content_language='$lang' WHERE completion_status IS NOT NULL";
    $completion = getDataAside("completion_status", $completion_query);
    // If not NULL...
    if (!empty($completion)) {
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


function getDownloads($id, $v=null, $lang=null) {
    include("populate.php");
    $config = getJSONConfigVariables();
    $paths = translateToPath($id, $v, $lang);

    // Find any files with the .pdf, .docx, .epub, or .zip extension.
    $files = glob($paths["content"] . "*.{pdf,docx,epub,zip}", GLOB_BRACE);

    // Create an <a> for each file.
    foreach ($files as $file) {
        // Get the file extension.
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        // Get the file name.
        $name = basename($file, "." . $ext);

        // Create the <a> tag.
        echo "<a href='" . $name . "." . $ext . "' download>" . strtoupper($ext) . "</a>\n";
    }
}


function getSettings($id, $lang, $v) {
    include("db_connect.php");

    $successes = 0;

    // VERSIONS
    $sql = "SELECT DISTINCT content_version FROM story_content WHERE id=\"$id\" ORDER BY content_version";
    // Perfom selection.
    $result = $mysqli->query($sql);

    if ($result->num_rows > 1) {
        echo "<fieldset>";
        echo "<select onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        while ($row = $result->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            // https://stackoverflow.com/questions/7562095/redirect-on-select-option-in-select-box
            $newv = $row["content_version"];

            $sqlnext = "SELECT version_title FROM story_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"en\" LIMIT 1";
            // IFNULL(SELECT content_language FROM story_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

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
    $sql = "SELECT DISTINCT content_language FROM story_content WHERE id=\"$id\" AND content_version=\"$v\" ORDER BY content_language";
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

    // EQUIVALENTS
    $sql_eq = "SELECT right_id AS alt_id, right_version AS alt_v FROM story_equivalents WHERE left_id=\"$id\" AND left_version=\"$v\" UNION ALL SELECT left_id AS alt_id, left_version AS alt_v FROM story_equivalents WHERE right_id=\"$id\" AND right_version=\"$v\"";
    $result_eq = $mysqli->query($sql_eq);

    if ($result_eq->num_rows > 0) {
        echo "<fieldset>";
        echo "<select id=\"equivalentSelect\" onchange=\"goTo(this.options[this.selectedIndex].value)\">";
        echo "<option selected=\"true\" disabled=\"disabled\">Equivalent stories…</option>";
        while ($row = $result_eq->fetch_assoc()) {
            // Get title for current version number, either in current language (if available) or English (if not).
            // https://stackoverflow.com/questions/7562095/redirect-on-select-option-in-select-box
            $newv = $row["alt_v"];
            $newid = $row["alt_id"];

            $sqlnext = "SELECT title FROM story_content WHERE id=\"$newid\" AND content_version=\"$newv\" LIMIT 1";
            // IFNULL(SELECT content_language FROM story_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

            $resultnext = $mysqli->query($sqlnext);
            while ($rownext = $resultnext->fetch_assoc()) {
                echo "<option value=\"" . $newid . "." . $newv . "\">" . $rownext["title"] . "</option>";
            }
        }
        echo "</select>";
        echo "</fieldset>";
        $successes++;
    }

    if ($successes != 0) {
        echo "<hr>\n";
    }
}


function getSettingsReference($id, $v, $lang) {
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
            // IFNULL(SELECT content_language FROM story_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

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
}


function getExtras($id, $v, $lang) {
    include("db_connect.php");

    $extra_query = "SELECT extra_type FROM story_reference_extras WHERE id='$id' AND content_version=$v AND content_language='$lang'";
    $extras = getDataAside("extra_type", $extra_query);

    if (!empty($extras)) {
        foreach ($extras as $extra) {
            echo "<button class='small' onclick='zoomExtras(\"$extra\");'>$extra</button>";
        }
        echo "<hr>\n";
    }
}


function getDownload($id, $lang) {
    include("db_connect.php");

    $download_query = "SELECT title FROM story_content WHERE id='$id' AND (content_language='$lang' OR content_language='en') LIMIT 1";
    $download_title = getDataAside("title", $download_query);

    if (isset($download_title[0])) {
        if (file_exists("../doc/downloads/" . $id . ".zip")) {
            echo "<a id='downloadLink' href='/doc/downloads/$id.zip' download='" . strip_tags($download_title[0]) . ".zip' target='_blank'><button class='small' id='downloadButton'>Download</button></a>\n";
            echo "<hr>\n";
        }
    }
}


function populateAside($id, $lang, $v) {
    include("db_connect.php");

    if (strpos($_SERVER["REQUEST_URI"], "read") !== false) {
        // 1. Echo main menu.
        // 2. Echo details (snippet [.snippet], release date [if any], word count [if any]).
        // 2.i. If any of above are NOT NULL, echo <hr>.
        getDetailsAside($id, $lang, $v);
        // 3. Echo version selectors.
        // 3.i. If any of above are NOT NULL, echo <hr>.
        getSettings($id, $lang, $v);
        // 4. Echo extras.
        // 4.i. If any of above are NOT NULL, echo <hr>.
        getExtras($id, $v, $lang);
        // 5. If file exists with ID, echo download link and <hr>.
        getDownload($id, $lang);
        // 6. Echo universal settings buttons.
    } else if (strpos($_SERVER["REQUEST_URI"], "reference") !== false) {
        // 1. Echo details (snippet [.snippet], release date [if any], word count [if any]).
        // 1.i. If any of above are NOT NULL, echo <hr>.
        // getDetailsAsideReference($id, $v, $lang);
        // 2. Echo version selectors.
        // 2.i. If any of above are NOT NULL, echo <hr>.
        getSettingsReference($id, $v, $lang);
    }
}