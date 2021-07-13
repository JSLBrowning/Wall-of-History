<?php
include("..//php/db_connect.php");

// Create selection statement.
$sql = "SELECT id, chronology, recommended FROM woh_metadata WHERE (recommended IS NOT NULL AND chronology IS NOT NULL) ORDER BY chronology ASC";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $r = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $r = $r . $row['id'] . ':' . $row['recommended'] . ',';
    }
    echo substr($r, 0, -1);
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
