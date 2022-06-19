<?php
function populateTitle($id)
{
    if ($id != "0") {
        include("db_connect.php");

        $sql_id = "SELECT IFNULL((SELECT entry_id FROM reference_metadata WHERE subject_id = '$id' LIMIT 1), '$id') AS id";
        $result_id = $mysqli->query($sql_id);
        if (mysqli_num_rows($result_id) > 0) {
            while ($row_id = mysqli_fetch_assoc($result_id)) {
                $id = $row_id["id"];
                $sql_title = "SELECT title FROM reference_titles WHERE entry_id = '$id' ORDER BY LENGTH(title) LIMIT 1";
                $result_title = $mysqli->query($sql_title);
                if (mysqli_num_rows($result_title) > 0) {
                    while ($row_title = mysqli_fetch_assoc($result_title)) {
                        echo strip_tags($row_title["title"]) . " | Wall of History";
                    }
                }
            }
        }
    } else {
        echo "Reference | Wall of History";
    }
}


function populateReferenceSubjects()
{
    include("db_connect.php");
    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h1>Reference</h1></div></section></section><section class='structure'><h2>Sources</h2><section class='structure'>";
    // Create selection statement.
    $sql = "SELECT entry_id, title FROM reference_titles WHERE entry_id NOT IN (SELECT child_id FROM woh_web) ORDER BY title ASC";
    // Perfom selection.
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='padding'><button class='contentsButton' onclick=\"window.location.href='/reference/?id=" . $row["entry_id"] . "';\"><div class='contentButtonText'><p>" . $row['title'] . "</p></div></button></div>";
        }
        echo "</section><h2>Subjects</h2><section class='structure'>";
    } else {
        echo "ERROR: Query failed. Please report to admin@wallofhistory.com.";
    }

    $sql = "SELECT DISTINCT subject_id FROM reference_metadata";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $entryid = $row["subject_id"];

            // Get name.
            $sql_name = "SELECT DISTINCT title FROM reference_titles WHERE entry_id IN (SELECT entry_id FROM reference_metadata WHERE subject_id='$entryid') LIMIT 1";
            $result_name = $mysqli->query($sql_name);

            // Get image, if any.
            $sql_image = "SELECT DISTINCT image_path, caption FROM reference_images WHERE id IN (SELECT reference_metadata.entry_id FROM reference_metadata JOIN reference_content ON reference_metadata.entry_id=reference_content.entry_id WHERE reference_metadata.subject_id='$entryid' ORDER BY reference_content.spoiler_level) AND image_path NOT LIKE '%.mp4%' LIMIT 1";
            $result_image = $mysqli->query($sql_image);
            $img = "";
            if ($result_image->num_rows > 0) {
                $row_image = mysqli_fetch_assoc($result_image);
                $img = "<img src='" . $row_image['image_path'] . "' alt='" . $row_image['caption'] . "'>";
            }

            if ($result_name->num_rows > 0) {
                while ($row_name = mysqli_fetch_assoc($result_name)) {
                    echo "<div class='padding'><button class='contentsButton' onclick=\"window.location.href='/reference/?id=" . $entryid . "';\">" . $img . "<div class='contentButtonText'><p>" . $row_name['title'] . "</p></div></button></div>";
                }
            }
        }
    }
    echo "</section></section>";
}


function populateReferenceSubjectPage($subject, $lang)
{
    include("db_connect.php");
    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h3><a onclick='window.location.href=\"/reference/\"'>Reference</a></h3></div></section>";

    $sql = "SELECT main FROM reference_content WHERE entry_id IN (SELECT DISTINCT entry_id FROM reference_metadata WHERE subject_id='" . $_GET['id'] . "')";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row["main"];
    }
    $sql_appreances = "SELECT DISTINCT story_id FROM reference_appearances WHERE subject_id='" . $_GET['id'] . "'";
    $result_appreances = $mysqli->query($sql_appreances);
    echo "<p>Appears in: <p>";
    while ($row = $result_appreances->fetch_assoc()) {
        echo "<button onclick='goTo(\"" . $row["story_id"] . "\")'>" . $row["story_id"] . "</button> ";
    }

    echo "</section>";
}


function populateReferenceContent($id, $lang)
{
    if ($id == "0") {
        populateReferenceSubjects();
    } else {
        populateReferenceSubjectPage($id, $lang);
    }
}
