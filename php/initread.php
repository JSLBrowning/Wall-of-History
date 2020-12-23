<?php
include("..//php/db_connect.php");

// Create selection statement.
$sql = "SELECT id, recommended FROM wall_of_history_contents WHERE childless=1 ORDER BY id ASC";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $r = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $r = $r . $row['id'] . ':' . $row['recommended'] . ',';
    }
    echo substr($r, 0, -1);
} else {
    echo "That's not good.";
}
?>