<?php
date_default_timezone_set('America/New_York');

// Generic query function.
function getDataAside($column, $query)
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


function getAdaptedFrom($id) {
    $successes = 0;

    $originals_query = "SELECT original_id FROM story_adaptations WHERE adaptation_id = '$id'";
    $originals = getDataAside("original_id", $originals_query);
    if (!empty($originals)) {
        $title_query = "SELECT title FROM woh_content WHERE id = '" . $originals[0] . "' AND content_version = 1 LIMIT 1";
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
        $title_query = "SELECT title FROM woh_content WHERE id = '" . $adaptations[0] . "' AND content_version = 1 LIMIT 1";
        $title = getDataAside("title", $title_query);

        echo "<p>Adapted into <a onclick=\"goTo('" . $adaptations[0] . "')\">" . $title[0] . "</a>.</p>\n";
        $successes++;
    }

    return $successes;
}


function getDetailsAside($id, $lang, $v) {
    include("..//php/db_connect.php");

    $successes = 0;

    // if != subtitle
    $snippet_query = "SELECT snippet FROM woh_content WHERE id='$id' AND content_version='$v' AND content_language='$lang'";
    $snippet = getDataAside("snippet", $snippet_query);
    if (!empty($snippet)) {
        echo "<p class='snippet'>" . $snippet[0] . "</p>\n";
        $successes++;
    }

    $successes = $successes + getAdaptedFrom($id);

    $release_query = "SELECT publication_date FROM woh_metadata WHERE id='$id'";
    $release = getDataAside("publication_date", $release_query);
    if (!empty($release)) {
        echo "<p>Released on " . date('F jS, Y', strtotime($release[0])) . ".</p>\n";
        $successes++;
    }

    $successes = $successes + getAdaptedInto($id);

    $words_query = "SELECT word_count FROM woh_content WHERE id='$id' AND content_version='$v' AND content_language='$lang'";
    $words = getDataAside("word_count", $words_query);
    if (isset($words[0])) {
        echo "<p>Word Count: " . $words[0] . "</p>\n";
        $successes++;
    } else {
        $children = getLeaves($id);
        // Check Stack Overflow to see if anyone's answered that question.
        $words_query_sum = "SELECT SUM(word_count) AS word_count FROM woh_content WHERE id IN ($children) AND content_version=1 AND content_language='$lang'";
        $words_sum = getDataAside("word_count", $words_query_sum);
        if (!is_null($words_sum[0])) {
            echo "<p>Word Count: " . $words_sum[0] . "</p>\n";
            $successes++;
        }
    }

    if ($successes != 0) {
        echo "<hr>\n";
    }
}


function getSettings($id, $lang, $v) {
    include("..//php/db_connect.php");

    $successes = 0;

    // VERSIONS
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
        $successes++;
    }

    // LANGUAGES
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

            $sqlnext = "SELECT title FROM woh_content WHERE id=\"$newid\" AND content_version=\"$newv\" LIMIT 1";
            // IFNULL(SELECT content_language FROM woh_content WHERE id=\"$id\" AND content_version=\"$newv\" AND content_language=\"$lang\", \"en\")

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


function getDownload($id, $lang) {
    include("..//php/db_connect.php");

    $download_query = "SELECT title FROM woh_content WHERE id='$id' AND (content_language='$lang' OR content_language='en') LIMIT 1";
    $download_title = getDataAside("title", $download_query);

    if (isset($download_title[0])) {
        if (file_exists("../doc/downloads/" . $id . ".zip")) {
            echo "<a id='downloadLink' href='/doc/downloads/$id.zip' download='" . strip_tags($download_title[0]) . ".zip' target='_blank'><button class='small' id='downloadButton'>Download</button></a>\n";
            echo "<hr>\n";
        }
    }
}


function populateAside($id, $lang, $v) {
    include("..//php/db_connect.php");

    // 1. Echo main menu.
    // 2. Echo details (snippet [.snippet], release date [if any], word count [if any]).
    // 2.i. If any of above are NOT NULL, echo <hr>.
    getDetailsAside($id, $lang, $v);
    // 3. Echo version selectors.
    // 3.i. If any of above are NOT NULL, echo <hr>.
    getSettings($id, $lang, $v);
    // 4. Echo extras.
    // 4.i. If any of above are NOT NULL, echo <hr>.
    // 5. If file exists with ID, echo download link and <hr>.
    getDownload($id, $lang);
    // 6. Echo universal settings buttons.
}