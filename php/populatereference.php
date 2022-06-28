<?php
date_default_timezone_set('America/New_York');

function populateTitle($id)
{
    if ($id != "0") {
        include("db_connect.php");

        $sql_id = "SELECT IFNULL((SELECT entry_id FROM reference_subjects WHERE subject_id = '$id' LIMIT 1), '$id') AS id";
        $result_id = $mysqli->query($sql_id);
        if (mysqli_num_rows($result_id) > 0) {
            while ($row_id = mysqli_fetch_assoc($result_id)) {
                $id = $row_id["id"];
                $sql_title = "SELECT title FROM reference_titles WHERE entry_id = '$id' ORDER BY LENGTH(title) LIMIT 1";
                $result_title = $mysqli->query($sql_title);
                if (mysqli_num_rows($result_title) > 0) {
                    while ($row_title = mysqli_fetch_assoc($result_title)) {
                        echo strip_tags($row_title["title"]) . " | Wall of History";
                    }
                }
            }
        }
    } else {
        echo "Reference | Wall of History";
    }
}


function populateReferenceChildren($parent_id, $v, $lang)
{
    include("db_connect.php");

    $sql_children = "SELECT reference_metadata.entry_id AS id, reference_metadata.publication_date AS pub_date, reference_content.snippet AS snippet, reference_content.word_count AS words FROM reference_metadata JOIN reference_content ON reference_metadata.entry_id=reference_content.entry_id WHERE reference_metadata.entry_id NOT IN (SELECT DISTINCT child_id FROM woh_web) AND reference_content.content_language='$lang' ORDER BY reference_metadata.publication_date ASC";
    if ($parent_id != "0") {
        $sql_children = "SELECT DISTINCT reference_metadata.entry_id AS id, reference_metadata.publication_date AS pub_date, reference_content.snippet AS snippet, reference_content.word_count AS words FROM reference_metadata JOIN reference_content ON reference_metadata.entry_id=reference_content.entry_id WHERE reference_metadata.entry_id IN (SELECT child_id FROM woh_web WHERE parent_id='$parent_id') AND reference_content.content_language='$lang' ORDER BY reference_metadata.chronology ASC";
        // AND parent_version=$v) AND reference_content.content_version=$v
        // May now produce multiple cards for each version of a thing. Need to fix that.
    }
    $result_children = $mysqli->query($sql_children);

    if (mysqli_num_rows($result_children) > 0) {
        while ($row_children = mysqli_fetch_assoc($result_children)) {
            $id = $row_children["id"];
            $date = $row_children["pub_date"];
            $snippet = $row_children["snippet"];
            $words = $row_children["words"];
            $sql_child_title = "SELECT title FROM reference_titles WHERE entry_id='$id' AND (title_version='$v' OR title_version IS NULL) AND (title_language='$lang' OR title_language IS NULL) ORDER BY title_order DESC LIMIT 1";
            $result_child_title = $mysqli->query($sql_child_title);

            $sql_child_image = "SELECT image_path, caption FROM reference_images WHERE entry_id='$id' AND (image_version=$v OR image_version IS NULL) AND (image_language='$lang' OR image_language IS NULL) AND image_path NOT LIKE '%.mp4%' ORDER BY image_order DESC LIMIT 1";
            $result_child_image = $mysqli->query($sql_child_image);
            $img = "";
            if (mysqli_num_rows($result_child_image) > 0) {
                while ($row_child_image = mysqli_fetch_assoc($result_child_image)) {
                    $img = "<img src='" . $row_child_image["image_path"] . "' alt='" . $row_child_image["caption"] . "'>";
                }
            } else {
                if (file_exists("..//img/reference/contents/" . $id . ".PNG")) {
                    $img = "<img src='/img/reference/contents/" . $id . ".png' alt='No image available'>";
                }
            }

            if (mysqli_num_rows($result_child_title) > 0) {
                while ($row_child_title = mysqli_fetch_assoc($result_child_title)) {
                    $title = $row_child_title["title"];

                    echo "<div class='padding'><button class='contentsButton' id='card$id' onclick=\"window.location.href='/reference/?id=" . $id . "';\">" . $img . "<div class='contentButtonText'><p>" . $title . "</p><p>" . $snippet . "</p><div class='versions'><p>RELEASED " . date('F jS, Y', strtotime($date)) . "</p><p>WORD COUNT: " . $words . "</p></div></div></button></div>";
                }
            }
        }
    }
}


function populateAllSubjects()
{
    include("db_connect.php");

    $sql = "SELECT DISTINCT subject_id FROM reference_subjects";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $entryid = $row["subject_id"];

            // Get name.
            $sql_name = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$entryid') LIMIT 1";
            $result_name = $mysqli->query($sql_name);

            // Get snippet.
            $sql_snippet = "SELECT DISTINCT snippet FROM reference_content WHERE entry_id IN (SELECT entry_id FROM reference_subjects WHERE subject_id='$entryid') LIMIT 1";
            $result_snippet = $mysqli->query($sql_snippet);
            $snippet = '';
            if (mysqli_num_rows($result_snippet) > 0) {
                while ($row_snippet = mysqli_fetch_assoc($result_snippet)) {
                    $snippet = "<p>" . $row_snippet["snippet"] . "</p>";
                }
            }

            // Get image, if any.
            $sql_image = "SELECT DISTINCT image_path, caption FROM reference_images WHERE subject_id='$entryid' AND entry_id IN (SELECT reference_content.entry_id FROM (reference_subjects JOIN reference_content ON reference_subjects.entry_id=reference_content.entry_id) JOIN reference_images ON reference_subjects.subject_id=reference_images.subject_id WHERE reference_subjects.subject_id='$entryid' ORDER BY reference_content.spoiler_level ASC) AND image_path NOT LIKE '%.mp4%' LIMIT 1";
            $result_image = $mysqli->query($sql_image);
            $img = "";
            if ($result_image->num_rows > 0) {
                $row_image = mysqli_fetch_assoc($result_image);
                $img = "<img src='" . $row_image['image_path'] . "' alt='" . $row_image['caption'] . "'>";
            }

            if ($result_name->num_rows > 0) {
                while ($row_name = mysqli_fetch_assoc($result_name)) {
                    echo "<div class='padding'><button class='contentsButton' id='card$entryid' onclick=\"window.location.href='/reference/?id=" . $entryid . "';\">" . $img . "<div class='contentButtonText'><p>" . $row_name['title'] . $snippet . "</p></div></button></div>";
                }
            }
        }
    }
}


function populateReferenceHomepage()
{
    include("db_connect.php");
    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h1>Reference</h1></div></section></section><section class='structure'>";
    populateReferenceChildren("0", "1", "en");
    echo "</section>";
}


function populateReferenceSubjectPage($subject, $lang)
{
    include("db_connect.php");
    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h3><a onclick='window.location.href=\"/reference/\"'>Reference</a></h3></div></section>";

    $sql = "SELECT main FROM reference_content WHERE entry_id IN (SELECT DISTINCT entry_id FROM reference_subjects WHERE subject_id='" . $_GET['id'] . "')";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row["main"];
    }
    $sql_appreances = "SELECT DISTINCT story_id FROM reference_appearances WHERE subject_id='" . $_GET['id'] . "'";
    $result_appreances = $mysqli->query($sql_appreances);
    echo "<p>Appears in: <p>";
    while ($row = $result_appreances->fetch_assoc()) {
        echo "<button onclick='goTo(\"" . $row["story_id"] . "\")'>" . $row["story_id"] . "</button> ";
    }

    echo "</section>";
}


function populateReferenceParentPage($parent, $v, $lang)
{
    include("db_connect.php");
    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h3><a onclick='window.location.href=\"/reference/\"'>Reference</a></h3></div></section>";

    $sql = "SELECT main FROM reference_content WHERE entry_id='$parent' AND content_language='$lang'";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row["main"];
    }

    echo "</section>";

    echo "<section class='structure'>";
    populateReferenceChildren($parent, $v, $lang);
    echo "</section>";
}


function populateReferenceContent($id, $v, $lang)
{
    if ($id == "0") {
        populateReferenceHomepage();
    } else {
        include("db_connect.php");

        $sql_subjects = "SELECT DISTINCT subject_id FROM reference_subjects";
        $result_subjects = $mysqli->query($sql_subjects);
        $subjects = array();
        if ($result_subjects->num_rows > 0) {
            while ($row_subjects = $result_subjects->fetch_assoc()) {
                array_push($subjects, $row_subjects["subject_id"]);
            }
        }

        $sql_entries = "SELECT DISTINCT entry_id FROM reference_metadata";
        $result_entries = $mysqli->query($sql_entries);
        $entries = array();
        if ($result_entries->num_rows > 0) {
            while ($row_entries = $result_entries->fetch_assoc()) {
                array_push($entries, $row_entries["entry_id"]);
            }
        }

        if (in_array($id, $entries)) {
            populateReferenceParentPage($id, $v, $lang);
        } else if (in_array($id, $subjects)) {
            populateReferenceSubjectPage($id, $lang);
        }
    }
}
