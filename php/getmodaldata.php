<?php
include("db_connect.php");

/********************************************
 * Update all of this for language support. *
 ********************************************/

// get the q parameter from URL
$q = $_REQUEST["q"];
$sl = $_REQUEST["sl"];

// Fetch all subjects that use this name (within the current spoiler level).
$sql_subjects = "SELECT DISTINCT subject_id FROM reference_subjects WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$q')";

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


/*
 * Function to fetch alternate names for a given subject.
 * Used to generate disambiguation buttons and on correctly-loaded modals
 */
function get_alternate_names($subject_id, $name, $spoiler_level) {
    include("db_connect.php");

    $sql_titles = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$subject_id') AND entry_id IN (SELECT entry_id FROM reference_content WHERE spoiler_level<=$spoiler_level) AND title != '$name'";
    $result_titles = $mysqli->query($sql_titles);

    $alt_names = [];
    if ($result_titles->num_rows > 0) {
        while ($row_titles = mysqli_fetch_assoc($result_titles)) {
            array_push($alt_names, $row_titles['title']);
        }
        return "<h2 class='altNames'>AKA " . implode(", ", $alt_names) . "</h2>";
    } else {
        return "";
    }
}


function get_source($entry_id) {
    include("db_connect.php");

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
    include("db_connect.php");

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
    include("db_connect.php");

    // Create selection statement for images.
    $sql_images = "SELECT DISTINCT image_path, caption FROM reference_images WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$name') AND entry_id IN (SELECT entry_id FROM reference_content WHERE spoiler_level<=$spoiler_level ORDER BY spoiler_level DESC)";
    $result_images = $mysqli->query($sql_images);
    $image_count = $result_images->num_rows;

    if ($image_count == 1) {
        $row_images = mysqli_fetch_assoc($result_images);
        echo "<div class='mediaplayer'><div class='mediaplayercontents'><img src='" . $row_images['image_path'] . "' alt='".$row_images['caption']."'></div></div>";
    } else if ($image_count > 1) {
        echo "<div class='mediaplayer'><div class='mediaplayercontents'>";
        while ($row_images = mysqli_fetch_assoc($result_images)) {
            $path = $row_images['image_path'];
            if ((strpos($path, '.mp4') !== false) || (strpos($path, '.m4v') !== false)) {
                echo "<video controls><source src='$path' type='video/mp4'></video>";
            } else {
                echo "<img src='" . $row_images['image_path'] . "' alt='".$row_images['caption']."'>";
            }
        }
        echo "</div><div class='mediaplayercontrols'><button class='mediaplayerbutton' onclick='backNav(this)' style='display: none;'>&#8249;</button><div class='slidelocationdiv'><p class='slidelocation'>1 / $image_count</p></div><button class='mediaplayerbutton' onclick='forwardNav(this)'>&#8250;</button></div></div>";
    }

    $sql_main = "SELECT entry_id, main, spoiler_level FROM reference_content WHERE entry_id IN (SELECT entry_id FROM reference_subjects WHERE subject_id='$subject_id') ORDER BY spoiler_level ASC";
    $result_main = $mysqli->query($sql_main);

    $main = "<div class=\"modal-text\"><h1>$name</h1>" . get_alternate_names($subject_id, $name, $spoiler_level);

    if ($result_main->num_rows > 0) {
        while ($row_main = mysqli_fetch_assoc($result_main)) {
            $current_entry = $row_main['entry_id'];
            $current_main = $row_main['main'];
            $current_spoiler_level = $row_main['spoiler_level'];
            $doc = new DOMDocument();
            $doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$current_main);

            $sql_parent = "SELECT title FROM reference_titles WHERE entry_id IN (SELECT parent_id FROM story_reference_web WHERE child_id='$current_entry')";
            $result_parent = $mysqli->query($sql_parent);
            $parent_title = "";
            if ($result_parent->num_rows > 0) {
                $row_parent = mysqli_fetch_assoc($result_parent);
                $parent_title = $row_parent['title'];
            }

            $selector = new DOMXPath($doc);
            foreach($selector->query('//a[contains(attribute::class, "anchor")]') as $a ) {
                $a->parentNode->removeChild($a);
            }

            if ($current_spoiler_level > $spoiler_level) {
                $main .= "<button class=\"hideShow\" onclick=\"hideShow(this)\"><span class='rightarrow'></span>SOURCE: $parent_title (Potential Spoilers)</button>";
                $main .= "<section class=\"showable\" style=\"display: none;\">" . $doc->saveXML() . "</section>";
            } else {
                $main .= "<button class=\"hideShow\" onclick=\"hideShow(this)\"><span class='downarrow'></span>SOURCE: $parent_title</button>";
                $main .= "<section class=\"showable\">" . $doc->saveXML() . "</section>";
            }
        }

        $main .= "</div>";
        echo $main;
    }
}
