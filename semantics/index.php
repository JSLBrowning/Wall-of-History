<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semantic Tags</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <?php
    echo "<table><tr><th>Item</th><th>Tags</th></tr>";

    include("..//php/db_connect.php");
    $sql = "SELECT DISTINCT tag FROM story_tags WHERE tag_type=\"semantic\"";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        $tag = $row["tag"];
        $arr = explode(".", $tag);
        $id = $arr[0];
        $v = $arr[1];
        $lang = $arr[2];

        $sql2 = "SELECT id, title, content_version, version_title FROM story_content WHERE id=\"$id\" AND content_version=$v AND content_language=\"$lang\"";
        $result2 = $mysqli->query($sql2);
        while ($row2 = $result2->fetch_assoc()) {
            $sql_chapter = "SELECT tag FROM story_tags WHERE (id = '" . $row2["id"] . "' AND tag = 'chapter')";

            $result_chapter = $mysqli->query($sql_chapter);
            $num_chap = mysqli_num_rows($result_chapter);
            if ($num_chap == 0) {
                echo "<tr><td>" . $row2["title"] . "</td><td style='font-family: monospace;'>";
            } else {
                $sql_title = "SELECT title FROM story_content JOIN story_reference_web ON story_reference_web.parent_id = story_content.id WHERE story_reference_web.child_id = '" . $row2["id"] . "' AND story_reference_web.child_version = " . $row2["content_version"];

                $result_title = $mysqli->query($sql_title);
                while ($row_title = $result_title->fetch_assoc()) {
                    echo "<tr><td>" . $row_title["title"] . ": " . $row2['title'] . "</td><td style='font-family: monospace;'>";
                }
            }

            $sql3 = "SELECT DISTINCT detailed_tag FROM story_tags WHERE tag=\"$tag\"";
            $result3 = $mysqli->query($sql3);
            while ($row3 = $result3->fetch_assoc()) {
                $detailed_tag = $row3["detailed_tag"];
                echo "<p style='margin:0.5em;'><a href=\"https://wallofhistory.com/read?s=" . $detailed_tag . "\">" . $detailed_tag . "</a></p>";
            }
            echo "</tr>";
        }
    }
    ?>
</body>

</html>