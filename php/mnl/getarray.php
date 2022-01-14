<?php
include("..//php/db_connect.php");

// get the q parameter from URL
$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT child_id, chronology FROM MnL_web JOIN MnL_metadata ON MnL_web.child_id = MnL_metadata.id WHERE MnL_web.parent_id = (SELECT parent_id FROM MnL_web WHERE child_id = \"" . $q . "\")";

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
