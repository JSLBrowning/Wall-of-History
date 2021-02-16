<?php
// Do I still NEED this?
include("..//php/db_connect.php");

// get the q parameter from URL
$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT title FROM woh_metadata WHERE id = \"" . $q . "\"";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row["title"];
    }
} else {
    echo "Wall of History Download";
}
?>