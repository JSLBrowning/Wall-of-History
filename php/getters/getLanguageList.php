<?php
include("db_connect.php");

// Create selection statement.
$sql = "SELECT GROUP_CONCAT(DISTINCT content_language /* ORDER BY COUNT(content_language) DESC */ SEPARATOR ',') FROM shin_content";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
