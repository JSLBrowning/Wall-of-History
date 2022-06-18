<?php
include("db_connect.php");


// get the q parameter from URL
$id = $_REQUEST["id"];


// Check if directory exists.
$dir = "../img/3d/" . $id;


// Try to get data.
if (file_exists($dir)) {
    if (file_exists($dir . "/cfg.txt")) {
        $data = file_get_contents($dir . "/cfg.txt");
        echo $data;
    } else {
        echo "false";
    }
} else {
    echo "false";
}
