<?php
include("..//php/db_connect.php");

// get the q parameter from URL
$q = $_REQUEST["q"];
$sl = $_REQUEST["sl"];

// Create selection statement for alternate titles.
// TO-DO: Ensure this is capitalization blind (this may have to be implemented in the JS).
$sql_titles = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$q' AND spoiler_level<=$sl ORDER BY spoiler_level ASC)";

// Perfom selection.
$result_titles = $mysqli->query($sql_titles);
$titles = [];
if ($result_titles->num_rows > 0) {
    while ($row_titles = mysqli_fetch_assoc($result_titles)) {
        array_push($titles, $row_titles['entry_id']);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}

// Create selection statement for images.
$sql_images = "SELECT image_path FROM reference_images WHERE entry_id IN (SELECT entry_id FROM reference_titles WHERE title='$q' AND spoiler_level<=$sl ORDER BY spoiler_level ASC)";