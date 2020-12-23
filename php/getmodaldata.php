<?php
include("..//php/db_connect.php");

// get the q parameter from URL
$q = $_REQUEST["q"];

// Create selection statement.
// $sql = "SELECT id, parent, fulltitle AS title, path FROM wall_of_history_contents WHERE childless=1 ORDER BY id ASC";
$sql = "SELECT content FROM wall_of_history_reference WHERE name = '$q'";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "That's not good.";
}
?>