<?php
// Do I still NEED this?
include("..//php/db_connect.php");

// get the q parameter from URL.
$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT parent_id FROM woh_web WHERE child_id = \"" . $q . "\"";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row["parent_id"];
    }
} else {
    echo "That's not good.";
}
