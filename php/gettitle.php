<?php
include("..//php/db_connect.php");

// get the q parameter from URL.
$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT title FROM woh_content WHERE id='$q' LIMIT 1";
// Find out where this is used.

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row["title"];
    }
} else {
    echo "Wall of History Download";
    // Why?
}
