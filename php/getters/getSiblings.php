<?php
include("db_connect.php");

// This function gets all siblings of a given child, ordered by chronology.

// get the q parameter from URL
$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT child_id, chronology FROM shin_web JOIN shin_metadata ON shin_web.child_id = shin_metadata.content_id WHERE shin_web.parent_id = (SELECT parent_id FROM shin_web WHERE child_id = \"" . $q . "\")";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $output = "";
    while ($row = $result->fetch_assoc()) {
        $output .= $row["child_id"] . ",";
    }
    echo substr($output, 0, -1);
} else {
    echo "NOTAPPLICABLE";
}
