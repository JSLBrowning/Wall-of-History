<?php
include("..//php/db_connect.php");

// get the q parameter from JS.
$id = $_REQUEST["id"];
$v = $_REQUEST["v"];

// Create selection statement.
$sql = "SELECT child_id, child_version, chronology FROM woh_metadata JOIN woh_web ON woh_metadata.id=woh_web.child_id WHERE (woh_metadata.recommended IS NOT NULL AND woh_metadata.chronology IS NOT NULL AND woh_web.parent_id='$id' AND woh_web.parent_version='$v') ORDER BY woh_metadata.chronology ASC";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $r = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $r = $r . $row["child_id"] . "." . $row["child_version"] . ":1,";
    }
    echo substr($r, 0, -1);
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
