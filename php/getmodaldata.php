<?php
include("..//php/db_connect.php");

/********************************************
 * Update all of this for language support. *
 ********************************************/

// get the q parameter from URL
$q = $_REQUEST["q"];
$sl = $_REQUEST["sl"];

// Fetch all subjects that use this name (within the current spoiler level).
$sql_subjects = "SELECT DISTINCT subject_id FROM reference_metadata WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$q')";

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
    echo "<p>No data is available at this time. Please check back later.</p>";
}


/**
 * Function to fetch alternate names for a given subject.
 * Used to generate disambiguation buttons and on correctly-loaded modals
 */
function get_alternate_names($subject_id, $name, $spoiler_level) {
    include("..//php/db_connect.php");

    $sql_titles = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$subject_id') AND entry_id IN (SELECT entry_id FROM reference_content WHERE spoiler_level<=$spoiler_level) AND title != '$name'";
    $result_titles = $mysqli->query($sql_titles);

    $alt_names = [];
    if ($result_titles->num_rows > 0) {
        while ($row_titles = mysqli_fetch_assoc($result_titles)) {
            array_push($alt_names, $row_titles['title']);
        }
        echo "<h2 class='altNames'>AKA " . implode(", ", $alt_names) . "</h2>";
    }
}


function get_source($entry_id) {
    include("..//php/db_connect.php");

    $sql_source = "SELECT source FROM reference_metadata WHERE entry_id='$entry_id'";
    $result_source = $mysqli->query($sql_source);

    if ($result_source->num_rows > 0) {
        $row_source = mysqli_fetch_assoc($result_source);
        echo "<p class='source'>Source: " . $row_source['source'] . "</p>";
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
    $sql_images = "SELECT DISTINCT image_path, caption FROM reference_images WHERE id IN (SELECT entry_id FROM reference_titles WHERE title='$name') AND id IN (SELECT entry_id FROM reference_content WHERE spoiler_level<=$spoiler_level ORDER BY spoiler_level DESC)";
    $result_images = $mysqli->query($sql_images);

    if ($result_images->num_rows == 1) {
        $row_images = mysqli_fetch_assoc($result_images);
        echo "<img class='slideshow' src='" . $row_images['image_path'] . "' alt='".$row_images['caption']."'>";
    } else if ($result_images->num_rows > 1) {
        $position = 0;
        $count = mysqli_num_rows($result_images);
        while ($row_images = mysqli_fetch_assoc($result_images)) {
            $noshow = " style='display:none'";
            $path = $row_images['image_path'];
            if ($position == $count - 1) {
                $noshow = "";
            }
            if ((strpos($path, '.mp4') !== false) || (strpos($path, '.m4v') !== false)) {
                echo "<video class='slideshow'$noshow controls><source src='$path' type='video/mp4'></video>";
            } else {
                echo "<img class='slideshow'$noshow src='" . $row_images['image_path'] . "' alt='".$row_images['caption']."'>";
            }
            $position++;
        }
    }

    echo "<h1>$name</h1>";
    get_alternate_names($subject_id, $name, $spoiler_level);

    $sql_main = "SELECT main FROM reference_content WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$subject_id' AND spoiler_level<=$spoiler_level) ORDER BY spoiler_level DESC";
    $result_main = $mysqli->query($sql_main);

    $main = "<div class=\"modal-text\">";
    if ($result_main->num_rows > 0) {
        while ($row_main = mysqli_fetch_assoc($result_main)) {
            $current_main = $row_main['main'];
            $doc = new DOMDocument();
            $doc->loadHTML($current_main);

            $selector = new DOMXPath($doc);
            foreach($selector->query('//a[contains(attribute::class, "anchor")]') as $a ) {
                $a->parentNode->removeChild($a);
            }

            $main .= "<button class=\"hideShow\" onclick=\"hideShow(this)\">⮟ SOURCE: [PUT TITLE HERE EVENTUALLY]</button>";
            $main .= "<section class=\"showable\">" . $doc->saveXML() . "</section>";
        }

        $main .= "</div>";
        echo $main;
    }
}
