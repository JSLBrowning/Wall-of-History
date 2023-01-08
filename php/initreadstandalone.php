<?php
include("db_connect.php");

// get the q parameter from JS.
$id = $_REQUEST["id"];
$v = $_REQUEST["v"];

// Create selection statement.
$sql = "SELECT DISTINCT child_id, default_version, chronology FROM story_metadata JOIN story_reference_web ON story_metadata.id=story_reference_web.child_id WHERE (story_metadata.recommended IS NOT NULL AND story_metadata.chronology IS NOT NULL AND story_reference_web.parent_id='$id' AND story_reference_web.parent_version='$v') ORDER BY story_metadata.chronology ASC";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $r = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $r = $r . $row["child_id"] . "." . $row["default_version"] . ":1,";
    }
    echo substr($r, 0, -1);
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
