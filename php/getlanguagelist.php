<?php
include("..//php/db_connect.php");

// Create selection statement.
$sql = "SELECT DISTINCT content_language FROM woh_content ORDER BY count(content_language)";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "That's not good.";
}
