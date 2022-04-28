<?php
include("..//php/db_connect.php");

// get the q parameter from URL
$q = $_REQUEST["q"];
$sl = $_REQUEST["sl"];

// Fetch all subjects that use this name (within the current spoiler level).
$sql_subjects = "SELECT DISTINCT subject_id FROM reference_metadata WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$q' AND spoiler_level<=$sl ORDER BY spoiler_level ASC)";

$result_subjects = $mysqli->query($sql_subjects);
$subjects = [];
if ($result_subjects->num_rows > 0) {
    while ($row_subjects = mysqli_fetch_assoc($result_subjects)) {
        array_push($subjects, $row_subjects['subject_id']);
    }

    if (count($subjects) > 1) {
        echo "<h1>Disambiguation for $q</h1>";
        // For these, we need:
        // 1. The highest ENTRY within the current spoiler level.
        // 2. The highest IMAGE within the spoiler level, if any (not necessarily associated with the above entry).
        // 3. The highest SNIPPET within the spoiler level, if any (not necessarily associated with the above entry).
        foreach ($subjects as $subject) {
            generate_disambiguation_buttons($subject, $q, $sl);
        }
    } else {
        get_one_subject($subjects[0], $q, $sl);
    }
} else {
    echo "<p>ERROR: Query failed. Please report to admin@wallofhistory.com.</p>";
}


/**
 * Function to fetch alternate names for a given subject.
 * Used to generate disambiguation buttons and on correctly-loaded modals
 */
function get_alternate_names($subject_id, $name, $spoiler_level) {
    include("..//php/db_connect.php");

    $sql_titles = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$subject_id' AND spoiler_level<=$spoiler_level) AND title != '$name' ORDER BY spoiler_level ASC";
    $result_titles = $mysqli->query($sql_titles);

    $alt_names = [];
    if ($result_titles->num_rows > 0) {
        while ($row_titles = mysqli_fetch_assoc($result_titles)) {
            array_push($alt_names, $row_titles['title']);
        }
        echo "<h2 class='altNames'>AKA " . implode(",", $alt_names) . "</h2>";
    }
}


/**
 * Function to prompt user for a selection when there is more than one subject with a given name.
 */
function generate_disambiguation_buttons($subject, $title, $sl) {
    include("..//php/db_connect.php");

    echo "<button onclick='reloadModal($subject)'>";

    $sql_image = "SELECT DISTINCT image_path, caption FROM reference_images WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$subject') WHERE spoiler_level<=$sl ORDER BY spoiler_level DESC";
    $result_image = $mysqli->query($sql_image);

    if ($result_image->num_rows > 0) {
        $row_image = mysqli_fetch_assoc($result_image);
        echo "<img src='" . $row_image['image_path'] . "' alt='".$row_image['caption']."'>";
    }

    echo "<div class='disambiguationButtonText'><h1>$title</h1>";

    get_alternate_names($subject, $title, $sl);

    $sql_snippet = "SELECT DISTINCT snippet FROM reference_metadata WHERE subject_id='$subject' AND spoiler_level<=$sl ORDER BY spoiler_level DESC LIMIT 1";
    $result_snippet = $mysqli->query($sql_snippet);

    if ($result_snippet->num_rows > 0) {
        $row_snippet = mysqli_fetch_assoc($result_snippet);
        echo "<p>" . $row_snippet['snippet'] . "</p>";
    }

    echo "</div></button>";
}


/**
 * Function to fetch data if only one subject ID is returned.
 */
function get_one_subject($subject_id, $name, $spoiler_level) {
    include("..//php/db_connect.php");

    // Create selection statement for images.
    $sql_images = "SELECT image_path, caption FROM reference_images WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$q' AND spoiler_level<=$spoiler_level ORDER BY spoiler_level DESC)";
    $result_images = $mysqli->query($sql_images);

    if ($result_images->num_rows == 1) {
        $row_images = mysqli_fetch_assoc($result_images);
        echo "<img src='" . $row_images['image_path'] . "' alt='".$row_images['caption']."'>";
    } else if ($result_images->num_rows > 1) {
        echo "<div class='modalSlideshow'>";
        while ($row_images = mysqli_fetch_assoc($result_images)) {
            echo "<img src='" . $row_images['image_path'] . "' alt='".$row_images['caption']."'>";
        }
        echo "</div>";
    }

    echo "<h1>$name</h1>";
    get_alternate_names($subject_id, $name, $spoiler_level);

    // Perfom selection.
    $result_titles = $mysqli->query($sql_titles);
    $titles = [];
    if ($result_titles->num_rows > 0) {
        while ($row_titles = mysqli_fetch_assoc($result_titles)) {
            array_push($titles, $row_titles['entry_id']);
        }
    } else {
        echo "<p>ERROR: Query failed. Please report to admin@wallofhistory.com.</p>";
    }
}
