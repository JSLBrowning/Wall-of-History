<?php
include("..//php/db_connect.php");

// Get the query.
$query = $_REQUEST["q"];
$column = $_REQUEST["c"];

$result = $mysqli->query($query);
$outputs = [];

if ($result->num_rows == 0) {
    echo "Query returned zero results. If you were not expecting this, please report to admin@wallofhistory.com";
} else if ($result->num_rows == 1) {
    $row = mysqli_fetch_assoc($result);
    echo $row[$column];
} else if ($result->num_rows > 1) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($outputs, $row[$column]);
    }
    echo implode(", ", $outputs);
} else {
    echo "<p>ERROR: Query failed. Please report to admin@wallofhistory.com.</p>";
}