<?php
include("..//php/db_connect.php");

// Create selection statement.
// TO-DO: Ensure this is capitalization blind (this may have to be implemented in the JS).
// Actually, ensure it ISN'T capitalization blind ("Ancient"), and that improper nouns (aero slicer) are in reference_titles with all capizalization variants.
$sql = "SELECT GROUP_CONCAT(DISTINCT title SEPARATOR ',') FROM reference_titles";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo array_shift($row);
    }
} else {
    echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
}
