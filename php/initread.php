<?php
include("db_connect.php");

// Create selection statement.
$sql = "SELECT id, chronology, recommended FROM story_metadata WHERE (recommended IS NOT NULL AND chronology IS NOT NULL AND id NOT IN (SELECT parent_id FROM story_reference_web)) ORDER BY chronology ASC";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $r = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $r = $r . $row['id'] . '.1:' . $row['recommended'] . ',';
    }
    echo substr($r, 0, -1);
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}

/* One day...
0. Relocate recommended column to story_content.
1. Get recommended version # of each ID, if any.
2. Get other stuff.
3. Et cetera...
*/