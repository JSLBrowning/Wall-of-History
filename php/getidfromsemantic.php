<?php
include("..//php/db_connect.php");

$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT id FROM woh_tags WHERE tag_type='semantic' AND tag='$q'";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
