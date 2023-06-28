<?php
include("db_connect.php");

// get the q parameter from URL
$id = $_REQUEST["id"];
$v = $_REQUEST["v"];

// Create selection statement.
$sql = "SELECT GROUP_CONCAT(DISTINCT content_language SEPARATOR ',') FROM story_content WHERE id='$id' AND content_version='$v'";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
