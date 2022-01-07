<?php
include("..//php/db_connect.php");
include("..//php/populate.php");
include("..//php/php-htmldiff/HtmlDiff.php");

// get the q parameter from URL
// LANGUAGES HAVE TO MATCH.
$id = $_REQUEST["id"];
$v = $_REQUEST["v"];
$v2 = $_REQUEST["v2"];
$lang = $_REQUEST["lang"];

// $oldhtml = loadContentWithReturn($id, $lang, $v);
// $newhtml = loadContentWithReturn($id, $lang, $v2);

$diff = new HtmlDiff($oldhtml, $newhtml);
$diff->build();
echo $diff->getDifference();