<?php
date_default_timezone_set('America/New_York');

function populateTitle($id)
{
    if ($id != "0") {
        include("db_connect.php");

        $sql_id = "SELECT IFNULL((SELECT entry_id FROM reference_subjects WHERE subject_id = '$id' LIMIT 1), '$id') AS id";
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


function populateReferenceChildren($parent_id, $v, $lang)
{
    include("db_connect.php");

    $sql_children = "SELECT DISTINCT(CONCAT(reference_metadata.entry_id, '.', reference_metadata.entry_version)) AS combo, reference_metadata.publication_date AS pub_date, reference_content.snippet AS snippet, reference_content.word_count AS words, IFNULL(reference_content.version_title, 'Standard Edition') AS vtitle FROM reference_metadata JOIN reference_content ON reference_metadata.entry_id=reference_content.entry_id WHERE reference_metadata.entry_id NOT IN (SELECT DISTINCT child_id FROM woh_web) AND reference_content.content_language='$lang' GROUP BY combo, pub_date, snippet, words, vtitle ORDER BY reference_metadata.publication_date ASC";
    if ($parent_id != "0") {
        $sql_children = "SELECT DISTINCT(CONCAT(reference_metadata.entry_id, '.', reference_metadata.entry_version)) AS combo, reference_metadata.publication_date AS pub_date, reference_content.snippet AS snippet, reference_content.word_count AS words, IFNULL(reference_content.version_title, 'Standard Edition') AS vtitle, reference_metadata.chronology FROM reference_metadata JOIN reference_content ON reference_metadata.entry_id=reference_content.entry_id WHERE reference_metadata.entry_id IN (SELECT child_id FROM woh_web WHERE parent_id='$parent_id') AND reference_content.content_language='$lang' GROUP BY combo, pub_date, snippet, words, vtitle, chronology ORDER BY reference_metadata.chronology ASC";
        // AND parent_version=$v) AND reference_content.content_version=$v
        // May now produce multiple cards for each version of a thing. Need to fix that.
    }
    $result_children = $mysqli->query($sql_children);

    if (mysqli_num_rows($result_children) > 0) {
        while ($row_children = mysqli_fetch_assoc($result_children)) {
            $id = explode(".", $row_children["combo"])[0];
            $newv = explode(".", $row_children["combo"])[1];
            $vtitle = $row_children["vtitle"];
            $date = $row_children["pub_date"];
            $snippet = $row_children["snippet"];
            $words = $row_children["words"];
            $sql_child_title = "SELECT title FROM reference_titles WHERE entry_id='$id' AND (title_version='$newv' OR title_version IS NULL) AND (title_language='$lang' OR title_language IS NULL) ORDER BY title_order DESC LIMIT 1";
            $result_child_title = $mysqli->query($sql_child_title);

            $sql_child_image = "SELECT image_path, caption FROM reference_images WHERE entry_id='$id' AND (image_version=$newv OR image_version IS NULL) AND (image_language='$lang' OR image_language IS NULL) AND image_path NOT LIKE '%.mp4%' AND image_path NOT LIKE '%.m4v%' ORDER BY image_order DESC LIMIT 1";
            $result_child_image = $mysqli->query($sql_child_image);
            $img = "";
            if (mysqli_num_rows($result_child_image) > 0) {
                while ($row_child_image = mysqli_fetch_assoc($result_child_image)) {
                    $img = "<img src='" . $row_child_image["image_path"] . "' alt='" . $row_child_image["caption"] . "'>";
                }
            } else {
                if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/img/reference/contents/" . $id . "." . $newv . "." . $lang . ".png")) {
                    $img = "<img src='/img/reference/contents/" . $id . "." . $newv . "." . $lang . ".png'>";
                } else if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/img/reference/contents/" . $id . ".png")) {
                    $img = "<img src='/img/reference/contents/" . $id . ".png'>";
                }
            }

            if (mysqli_num_rows($result_child_title) > 0) {
                while ($row_child_title = mysqli_fetch_assoc($result_child_title)) {
                    $title = $row_child_title["title"];

                    echo "<div class='padding'><button class='contentsButton' id='card$id' onclick=\"window.location.href='/reference/?id=" . $id . "&v=$newv';\">" . $img . "<div class='contentButtonText'><p>" . $title . "</p><p>" . $snippet . "</p><div class='versions'><p>$vtitle</p><p>RELEASED " . date('F jS, Y', strtotime($date)) . "</p><p>WORD COUNT: " . $words . "</p></div></div></button></div>";
                }
            }
        }
    }
}


function populateAllSubjects($page)
{
    include("db_connect.php");

    $skip = (intval($page) - 1) * 30;
    $sql = "SELECT DISTINCT GROUP_CONCAT(DISTINCT reference_titles.title) AS titles, reference_subjects.subject_id FROM reference_titles JOIN reference_subjects ON reference_titles.subject_id=reference_subjects.subject_id WHERE reference_subjects.subject_id IS NOT NULL GROUP BY reference_subjects.subject_id ORDER BY reference_titles.title ASC LIMIT $skip, 31";

    $result = $mysqli->query($sql);
    $more = false;
    if ($result->num_rows > 30) {
        $more = true;
    } else {
        $more = false;
    }

    echo "<section class='structure'>";

    if ($result->num_rows > 0) {
        $done = 0;
        while (($row = mysqli_fetch_assoc($result)) && ($done < 30)) {
            $subject = $row["subject_id"];
            $title = explode(",", $row["titles"]);
            $main_title = $title[0];
            $secondary_titles = "";
            if (count($title) > 1) {
                $secondary_titles = "<p>AKA " . implode(", ", array_slice($title, 1)) . "</p>";
            }
            echo "<div class='padding small'><button class='contentsButton small' id='card$subject' onclick=\"window.location.href='/reference/?id=" . $subject . "';\"><div class='contentButtonText'><p>" . $main_title . "</p>$secondary_titles</div></button></div>";
            $done++;
        }
    }

    echo "</section>";

    if ($more || ($skip != 0)) {
        echo "<div class='nav'>";
        if ($skip != 0) {
            echo "<button type='navbutton' onclick='goBack()' id='backbutton'><span class='leftarrow'></span></button>";
        }
        if ($more) {
            echo "<button type='navbutton' onclick='goForward()' id='forwardbutton'><span class='rightarrow'></span></button>";
        }
        echo "</div>";
    }
}


function populateReferenceSubjectPage($subject, $lang)
{
    include("db_connect.php");

    $sql_titles = "SELECT DISTINCT title FROM reference_titles WHERE subject_id='$subject' AND (title_language='$lang' OR title_language IS NULL) ORDER BY title ASC";
    $result_titles = $mysqli->query($sql_titles);
    $titles = "";
    if ($result_titles->num_rows == 1) {
        while ($row_titles = mysqli_fetch_assoc($result_titles)) {
            $title = $row_titles["title"];
            $titles .= "<h1>" . $title . "</h1>";
        }
    } else if ($result_titles->num_rows > 1) {
        $count = 0;
        $secondary_titles = array();
        while ($row_titles = mysqli_fetch_assoc($result_titles)) {
            if ($count == 0) {
                $title = $row_titles["title"];
                $titles .= "<h1>" . $title . "</h1>";
                $count++;
            } else {
                $title = $row_titles["title"];
                array_push($secondary_titles, $title);
            }
        }
        $titles .= "<h2>AKA " . implode(", ", $secondary_titles) . "</h2>";
    }


    // Get image from entry.
    $sql_images = "SELECT DISTINCT image_path, caption FROM reference_images WHERE subject_id='$subject' AND (image_language='$lang' OR image_language IS NULL) ORDER BY image_order ASC";
    $result_images = $mysqli->query($sql_images);
    $image_count = $result_images->num_rows;
    $images = '';

    if ($image_count == 1) {
        $row_images = mysqli_fetch_assoc($result_images);
        $images .= "<div class='mediaplayer'><div class='mediaplayercontents'><img src='" . $row_images['image_path'] . "' alt='" . $row_images['caption'] . "'></div></div>";
    } else if ($image_count > 1) {
        $images .= "<div class='mediaplayer'><div class='mediaplayercontents'>";
        while ($row_images = mysqli_fetch_assoc($result_images)) {
            $path = $row_images['image_path'];
            if ((strpos($path, '.mp4') !== false) || (strpos($path, '.m4v') !== false)) {
                $images .= "<video controls><source src='$path' type='video/mp4'></video>";
            } else {
                $images .= "<img src='" . $row_images['image_path'] . "' alt='" . $row_images['caption'] . "'>";
            }
        }
        $images .= "</div><div class='mediaplayercontrols'><button class='mediaplayerbutton' onclick='backNav(this)' style='display: none;'>&#8249;</button><div class='slidelocationdiv'><p class='slidelocation'>1 / $image_count</p></div><button class='mediaplayerbutton' onclick='forwardNav(this)'>&#8250;</button></div></div>";
    }
    

    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h3><a onclick='window.location.href=\"/reference/subjects/\"'>Reference</a></h3>$titles</div>$images</section>";


    $sql = "SELECT entry_id, content_version, main, version_title FROM reference_content WHERE entry_id IN (SELECT DISTINCT entry_id FROM reference_subjects WHERE subject_id='" . $_GET['id'] . "')";
    $result = $mysqli->query($sql);
    $row_cnt = $result->num_rows;
    $rows_done = 0;
    while ($row = $result->fetch_assoc()) {
        $id = $row["entry_id"];
        $version = $row["content_version"];
        $version_title = $row["version_title"];
        if ($version_title != "") {
            $version_title = "(" . $version_title . ")";
        }

        $doc = new DOMDocument();
        $doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$row["main"]);

        $selector = new DOMXPath($doc);
        foreach($selector->query('//a[contains(attribute::class, "anchor")]') as $a ) {
            $a->parentNode->removeChild($a);
        }

        echo $doc->saveXML();

        $sql_parent = "SELECT entry_id, title FROM reference_titles WHERE entry_id IN (SELECT parent_id FROM woh_web WHERE child_id='$id')";
        $result_parent = $mysqli->query($sql_parent);
        $parent_title = "";
        if ($result_parent->num_rows > 0) {
            $row_parent = mysqli_fetch_assoc($result_parent);
            $parent_title = $row_parent['title'];
            echo "<p class='center'>Source: <a href='/reference/?id=$id&v=$version'>$parent_title $version_title <span class='linkarrow'></span></a></p>";
        }

        $rows_done++;
        if ($rows_done != $row_cnt) {
            echo "<hr>";
        }
    }

    /*
    $sql_appreances = "SELECT DISTINCT story_id FROM reference_appearances WHERE subject_id='" . $_GET['id'] . "'";
    $result_appreances = $mysqli->query($sql_appreances);
    echo "<p>Appears in: <p>";
    while ($row = $result_appreances->fetch_assoc()) {
        echo "<button onclick='goTo(\"" . $row["story_id"] . "\")'>" . $row["story_id"] . "</button> ";
    }
    */

    echo "</section>";
}


function populateReferencePage($parent, $v, $lang)
{
    include("db_connect.php");
    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'>";


    // Get and list parent(s) of current content.
    $sql_parents = "SELECT DISTINCT parent_id, parent_version FROM woh_web WHERE child_id='$parent'";
    $result_parents = $mysqli->query($sql_parents);
    if ($result_parents->num_rows > 0) {
        while ($row_parents = $result_parents->fetch_assoc()) {
            $parent_id = $row_parents["parent_id"];
            $v = $row_parents["parent_version"];
            $sql_parent_title = "SELECT DISTINCT(title) FROM reference_titles WHERE entry_id='$parent_id' AND (title_version=$v OR title_version IS NULL) LIMIT 1";
            $result_parent_title = $mysqli->query($sql_parent_title);
            if ($result_parent_title->num_rows > 0) {
                while ($row_parent_title = $result_parent_title->fetch_assoc()) {
                    echo "<h3><a onclick='window.location.href=\"/reference/?id=" . $parent_id . "\"'>" . $row_parent_title["title"] . "</a></h3>";
                    break;
                }
            }
        }
    } else {
        echo "<h3><a onclick='window.location.href=\"/reference/\"'>Reference</a></h3>";
    }


    // Get and list title(s) of current content.
    $sql_titles = "SELECT DISTINCT title FROM reference_titles WHERE entry_id='$parent' AND (title_version=$v OR title_version IS NULL) AND (title_language='$lang' OR title_language IS NULL) ORDER BY title_order DESC";
    $result_titles = $mysqli->query($sql_titles);
    $title_count = $result_titles->num_rows;
    if ($title_count == 1) {
        while ($row_titles = $result_titles->fetch_assoc()) {
            echo "<h1>" . $row_titles["title"] . "</h1>";
        }
    } else if ($title_count > 1) {
        echo "<div class='multiparents'><button onclick='carouselBack(this)'><span class='leftarrow'></span></button>";
        while ($row_titles = $result_titles->fetch_assoc()) {
            echo "<h1>" . $row_titles["title"] . "</h1>";
        }
        echo "<button onclick='carouselForward(this)'><span class='rightarrow'></span></button></div>";
    }

    echo "</div></section>";


    // Get and display images, in a media player if necessary.
    $sql_images = "SELECT DISTINCT image_path, caption FROM reference_images WHERE entry_id='$parent' AND (image_version=$v OR image_version IS NULL) AND (image_language='$lang' OR image_language IS NULL) ORDER BY image_order DESC";
    $result_images = $mysqli->query($sql_images);
    $image_count = $result_images->num_rows;

    if ($image_count == 1) {
        $row_images = mysqli_fetch_assoc($result_images);
        echo "<div class='mediaplayer'><div class='mediaplayercontents'><img src='" . $row_images['image_path'] . "' alt='" . $row_images['caption'] . "'></div></div>";
    } else if ($image_count > 1) {
        echo "<div class='mediaplayer'><div class='mediaplayercontents'>";
        while ($row_images = mysqli_fetch_assoc($result_images)) {
            $path = $row_images['image_path'];
            if ((strpos($path, '.mp4') !== false) || (strpos($path, '.m4v') !== false)) {
                echo "<video controls><source src='$path' type='video/mp4'></video>";
            } else {
                echo "<img src='" . $row_images['image_path'] . "' alt='" . $row_images['caption'] . "'>";
            }
        }
        echo "</div><div class='mediaplayercontrols'><button class='mediaplayerbutton' onclick='backNav(this)' style='display: none;'>&#8249;</button><div class='slidelocationdiv'><p class='slidelocation'>1 / $image_count</p></div><button class='mediaplayerbutton' onclick='forwardNav(this)'>&#8250;</button></div></div>";
    }


    // Get main content.
    $sql = "SELECT main FROM reference_content WHERE entry_id='$parent' AND content_language='$lang'";
    $result = $mysqli->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo $row["main"];
    }


    // Close out main section and display child links, if any children.
    echo "</section><section class='structure'>";
    populateReferenceChildren($parent, $v, $lang);
    echo "</section>";
}


/*******************
 * MAIN POPULATORS *
 *******************/


function populateReferenceContent($id, $v, $lang, $pg)
{
    include("db_connect.php");

    if ($id == "0") {
        echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h1>Reference</h1></div></section><p class='center'>Currently organized by source. You can also organize by <a href='/reference/subjects/'>subject</a>.</p></section><section class='structure'>";
        populateReferenceChildren($id, $v, $lang);
        // populateAllSubjects($pg);
        echo "</section>";
    } else {

        $sql_subjects = "SELECT DISTINCT subject_id FROM reference_subjects";
        $result_subjects = $mysqli->query($sql_subjects);
        $subjects = array();
        if ($result_subjects->num_rows > 0) {
            while ($row_subjects = $result_subjects->fetch_assoc()) {
                array_push($subjects, $row_subjects["subject_id"]);
            }
        }

        $sql_entries = "SELECT DISTINCT entry_id FROM reference_metadata";
        $result_entries = $mysqli->query($sql_entries);
        $entries = array();
        if ($result_entries->num_rows > 0) {
            while ($row_entries = $result_entries->fetch_assoc()) {
                array_push($entries, $row_entries["entry_id"]);
            }
        }

        if (in_array($id, $entries)) {
            populateReferencePage($id, $v, $lang);
        } else if (in_array($id, $subjects)) {
            populateReferenceSubjectPage($id, $lang);
        }
    }
}


function populateReferenceContentSubjects($pg)
{
    include("db_connect.php");

    echo "<section class='story'><section class='titleBox'><div class='titleBoxText'><h1>Reference</h1></div></section><p class='center'>Currently organized by subject. You can also organize by <a href='/reference/'>sources</a>.</p></section>";
    populateAllSubjects($pg);
}
