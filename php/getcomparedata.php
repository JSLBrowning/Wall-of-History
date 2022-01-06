<?php
include("..//php/db_connect.php");
include("..//php/populate.php");

// get the q parameter from URL
// LANGUAGES HAVE TO MATCH.
$id = $_REQUEST["id"];
$v2 = $_REQUEST["v2"];
$lang = $_REQUEST["lang"];

/*
// Create selection statement.
// TO-DO: Ensure this is capitalization blind (this may have to be implemented in the JS).
$sql = "SELECT main FROM woh_content WHERE id = '$id' AND content_version = '$v' AND content_language = '$lang'";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
*/

loadContent($id, $lang, $v2);