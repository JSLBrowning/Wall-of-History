<?php
include("..//php/db_connect.php");

// get the q parameter from URL
$q = $_REQUEST["q"];

// Create selection statement.
$sql = "SELECT GROUP_CONCAT(DISTINCT content_language SEPARATOR ',') FROM woh_content WHERE id = \"" . $q . "\"";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
