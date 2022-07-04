<?php
include("db_connect.php");

// get the ID parameter from XmlHttpRequest.
$id = $_REQUEST["id"];
$v = $_REQUEST["v"];

// Create selection statement.
$sql = "SELECT parent_id FROM woh_web WHERE child_id='$id' AND child_version=$v";

// Perfom selection.
$result = $mysqli->query($sql);

if ($result->num_rows == 1) {
    // Continue.
    while ($row = $result->fetch_assoc()) {
        $parent_id = $row["parent_id"];

        // Get grandparent.
        $sql_grandparent = "SELECT parent_id FROM woh_web WHERE child_id='$parent_id'";
        $result_grandparent = $mysqli->query($sql_grandparent);

        if ($result_grandparent->num_rows == 1) {
            // Continue.
            while ($row_gp = $result_grandparent->fetch_assoc()) {
                $grandparent_id = $row_gp["parent_id"];
                $returnString = $id . "," . $parent_id . "," . $grandparent_id;
                echo $returnString;
            }
        } else {
            $returnString = $id . "," . $parent_id;
            echo $returnString;
        }
    }
} else {
    // If more than one parent, don’t bother — there will be more.
    echo $id;
}
