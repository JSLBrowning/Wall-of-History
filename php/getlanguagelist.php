<?php
include("..//php/db_connect.php");

// Create selection statement.
$sql = "SELECT GROUP_CONCAT(DISTINCT content_language /* ORDER BY COUNT(content_language) DESC */ SEPARATOR ',') FROM woh_content";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "That's not good.";
}
