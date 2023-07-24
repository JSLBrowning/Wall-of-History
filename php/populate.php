<?php


// Include Parsedown.
require("/parsedown-1.7.4/Parsedown.php");


// Get config.json variables.
// Access variables using $GLOBALS['config']['variable']. For example, $GLOBALS['config']['mainWork'].
$path = $_SERVER['DOCUMENT_ROOT'] . "/config/config.json";
$GLOBALS['config'] = json_decode(file_get_contents($path), true);


/****************
 * LABEL TABLES *
 ****************/

// This table is used to translate the language codes used in the database into human-readable language names.
$languages = [
    "en" => "English",
    "es" => "Español",
    "fr" => "Français",
    "de" => "Deutsch",
    "it" => "Italiano",
    "pt" => "Português",
    "ru" => "Русский",
    "ja" => "日本語",
    "ko" => "한국어",
    "zh" => "中文"
];


/**
 * HOW TO BUILD A READER PAGE:
 * 1. Ensure an ID, version, and language were passed to the page.
 * 2. If not, redirect to the table of contents.
 *    a. On table of contents, find tops of trees with chronology values (the chapters, basically).
 *    b. Include a dropdown menu for other options, including media type and release date.
 *    c. If media type, split into the four categories, with main at the top, followed by developmental, promotional, and supplemental.
 *       i. Then have one block for each type, with the title of the block being the type.
 *    d. If release date, find all elements with a release date. If all children of a parent have the same, only display the parent.
 * 3. If so, populate the <head> with OGP data.
 * 4. Populate the <head> with any relevant CSS links.
 * 5. Populate the <body> with the header.
 * 6. Populate the <body> with an article containing the main content (use a createArticle() function, maybe) according to the tags.
 *    a. Populate the titlebox with the...
 *       i. <img>
 *       ii. .title__box__text
 *          1. Parent(s) (if applicable).
 *          2. Title.
 *          3. Subtitle.
 *          4. Creators.
 *       iii. .title__box__buttons
 *       iv. .title__box__tags
 *    b. Populate .extra__content.
 *       i. .version__select
 *       ii. .language__select
 *       iii. any .detail <div>s
 *       iv. .extra__areas for downloads, development material, promotional material, and supplemental material.
 *          1. Use relevant Font Awesome icons for PDF, DOCX, e-book, et cetera, then use default for others.
 *    c. Populate the rest of the block with the main content.
 *       i. If the main content column is empty, attempt to load content from the relevant directory, as specified in config.json.
 *       ii. if (main == null) {$main = loadExternalContent(pathTemplate, UUID, version, language)}
 *       iii. If type is "comic", and folder contains images, create a slideshow, etc.
 * 7. Populate the <body> with a <div.deck> containing children. Can be sorted by…
 *    a. Release date. One list.
 *    b. Type. If *all* children share a certain type, remove it from groupings. Then do this recursively. If all videos are movies, remove the video type. Basically, be specific.
 *       i. If all children are of the same type, don't bother with the type header.
 *       ii. Types should also be collapsible.
 *       iii. "Story" and "ref" should be types?
 *    c. Chronology. Only if all children have a chronology value.
 * X. Run mods.
 *    a. Run any PHP files inside the /mods/ folder.
 *    b. Insert <script> tags for any JS files found in the /mods/ folder before the end of the <body> tag.
 */


/**
 * SPECIAL BEHAVIORS FOR TYPE:
 * 1. Comic: If folder contains numbered images, create a slideshow.
 * 2. Video: If folder contains a video file, create a video player.
 */


/**
 * WHAT TO DO WITH IMAGES:
 * Asset folders can contain:
 * 1. OGP images (/[id/s]/ogp.png or /ogp/[id/s].png).
 * 2. Titlebox images (/[id/s]/[anything].png /assets/[id/s].png).
 *    1. Can link to 3D assets (/[id/s]/3d.txt).
 * 3. Slideshow images (/[id/s]/[int].png /assets/[id/s]/[int].png).
 */


/**
 * FUNCTIONS TO ADD:
 * 1. checkForTag(tag, id, v (optional), lang (optional)) - check if a given entry has a given tag.
 * 2. getAllTags(id, v(optional), lang (optional)) - get an array of all distinct tags for an entry.
 * 3. All data fetching functions will need to be refactored into a function that gets JSON first, then database?
 */


/******************************
 * DATA TRANSLATION FUNCTIONS *
 ******************************/


// Fine, I'll do it myself.
// DELETE THIS when we transition to PHP 8.
function str_contains($haystack, $needle)
{
    return strpos($haystack, $needle) !== false;
}


// Function to fetch and decode the CONFIG.JSON file.
function getJSONConfigVariables()
{
    // $_SERVER['DOCUMENT_ROOT'] is used to create absolute paths.
    $path = $_SERVER['DOCUMENT_ROOT'] . "/config/config.json";
    $file = file_get_contents($path);
    $json = json_decode($file, true);
    return $json;
}


// This function overwrites overlapping elements in an array.
// Useful for the hardcoded cards in config.json.
function overwriteArrayElements($array, $overwrite)
{
    foreach ($overwrite as $key => $value) {
        $array[$key] = $value;
    }
    return $array;
}


// Function to translate a SEMANTIC TAG into a CONTENT ID, VERSION, and LANGUAGE.
function translateFromSemantic($semanticTag)
{
    include('db_connect.php');

    $semanticQuery = "SELECT content_id, content_version, content_language FROM shin_tags WHERE tag_type='semantic' AND tag='$semanticTag' LIMIT 1";
    $semanticResult = $mysqli->query($semanticQuery);
    $semanticRow = $semanticResult->fetch_assoc();
    $contentID = $semanticRow["content_id"];
    $contentVersion = $semanticRow["content_version"];
    $contentLanguage = $semanticRow["content_language"];

    // Put ID, version, and langauge into a dictionary, with the values being null if they're not present.
    $semanticData = array(
        "id" => $contentID,
        "v" => $contentVersion,
        "lang" => $contentLanguage,
        "s" => $semanticTag
    );

    return $semanticData;
}


// Function to translate a CONTENT ID, VERSION, and LANGUAGE into a SEMANTIC TAG.
function translateToSemantic($id, $v=null, $lang=null)
{
    include('./php/db_connect.php');

    $semanticQuery = "";
    if ($v != null && $lang != null) {
        $semanticQuery = "SELECT tag FROM shin_tags WHERE tag_type='semantic' AND content_id='$id' AND content_version=$v AND content_language='$lang' ORDER BY LENGTH(tag) LIMIT 1";
    } else if ($v != null && $lang == null) {
        $semanticQuery = "SELECT tag FROM shin_tags WHERE tag_type='semantic' AND content_id='$id' AND content_version=$v AND content_language IS NULL ORDER BY LENGTH(tag) LIMIT 1";
    } else if ($v == null && $lang != null) {
        $semanticQuery = "SELECT tag FROM shin_tags WHERE tag_type='semantic' AND content_id='$id' AND content_version IS NULL AND content_language='$lang' ORDER BY LENGTH(tag) LIMIT 1";
    } else {
        $semanticQuery = "SELECT tag FROM shin_tags WHERE tag_type='semantic' AND content_id='$id' AND content_version IS NULL AND content_language IS NULL ORDER BY LENGTH(tag) LIMIT 1";
    }

    $semanticResult = $mysqli->query($semanticQuery);
    if (mysqli_num_rows($semanticResult) > 0) {
        while ($row = $semanticResult->fetch_assoc()) {
            return $row["tag"];
        }
    } else {
        return 1;
    }
}


// Function to check if there are multiple versions of a given work, and return an array of those versions.
function checkForMultipleVersions($id)
{
    include("db_connect.php");
    $query = "SELECT content_version FROM shin_content WHERE content_id='$id'";
    $result = $mysqli->query($query);
    // Return result as an array.
    $versions = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($versions, $row["content_version"]);
        }
    }
    return $versions;
}


// Function to translate IDENTIFIERS into an HREF.
function getHREF($s = null, $id = null, $v = null, $lang = null)
{
    if (is_array($v)) {
        $v = implode(",", $v);
    }

    if (!is_null($s)) {
        return '/read/?s=$s';
    } else if (!is_null($id) && !is_null($v) && !is_null($lang)) {
        $semanticTag = translateToSemantic($id, $v, $lang);
        if ($semanticTag != 1) {
            return "/read/?s=$semanticTag";
        } else {
            return "/read/?id=$id&v=$v&lang=$lang";
        }
    } else if (!is_null($id) && !is_null($v)) {
        return "/read/?id=$id&v=$v";
    } else if (!is_null($id) && !is_null($lang)) {
        return "/read/?id=$id&lang=$lang";
    } else if (!is_null($id)) {
        return "/read/?id=$id";
    } else {
        return "<a href='/read/'>";
    }
}


// Function to translate a CONFIG ARRAY and a CONTENT ID into an array of possible asset paths (such as for images).
// Returns an array of multiple strings, each of which begins and ends with a /.
function translateToPath($config, $id, $v = 1, $lang = "en")
{
    include("db_connect.php");
    $content_path = $config["contentPath"];

    /**
     * IDENTIFIERS
     */

    // First things first, get all semantic tags for the content.
    $query_tags = "SELECT tag FROM shin_tags WHERE content_id='$id' AND tag_type='semantic' AND content_version=$v AND content_language='$lang'";
    $identifiers = [$id];
    // Add any/all semantic tags to the array.
    $result_tags = $mysqli->query($query_tags);
    if (mysqli_num_rows($result_tags) > 0) {
        while ($row_tags = $result_tags->fetch_assoc()) {
            $tag = $row_tags["tag"];
            array_push($identifiers, $tag);
        }
    }

    $paths = [];
    foreach ($identifiers as $identifier) {
        // Convert "[id/s]" in $content_path to $identifier, then push to $paths.
        array_push($paths, str_replace("[id/s]", $identifier, $content_path) . "/");
    }

    /**
     * TYPE TAGS
     */

    // If "[tagtype:type]" in $content_path, get all type tags for the content.
    if (strpos($content_path, "[tagtype:type]") !== false) {
        // Get all type tags for the content.
        $types = [];
        $query_types = "SELECT tag FROM shin_tags WHERE content_id='$id' AND tag_type='type'";
        $result_types = $mysqli->query($query_types);
        if (mysqli_num_rows($result_types) > 0) {
            while ($row_types = $result_types->fetch_assoc()) {
                array_push($types, $row_types["tag"]);
            }
        }

        // For each path, create a new path for each type.
        $new_paths = [];
        foreach ($paths as $path) {
            foreach ($types as $type) {
                array_push($new_paths, str_replace("[tagtype:type]", $type, $path));
            }
        }

        // Replace $paths with $new_paths.
        $paths = $new_paths;
    }

    return $paths;
}



/******************************
 * CONTENT FETCHING FUNCTIONS *
 ******************************/


// Function to get title, subtitle, and snippet from a content ID. Returns an array of arrays.
function getContentData($id, $v = null, $lang = null)
{
    include('db_connect.php');

    // If $v is not null...
    $version_conditonal = ($v != null) ? "AND content_version=$v" : "";
    $language_conditional = ($lang != null) ? "AND content_language='$lang'" : "";

    // If version is null, get all versions, and list them on the card (which links to version 1 by default).
    // If language is null, try user language, then default to English.
    $content_query = "SELECT version_title, content_title, content_subtitle, content_snippet, content_words FROM shin_content WHERE content_id='$id' $version_conditonal $language_conditional";
    $content_result = $mysqli->query($content_query);
    // Put all rows into an array.
    $content_rows = array();
    while ($row = $content_result->fetch_assoc()) {
        $content_rows[] = $row;
    }

    return $content_rows;
}


function getMainContent($id, $version = 1, $language = "en")
{
    include("db_connect.php");

    $query = "SELECT content_main FROM shin_content WHERE content_id='$id' AND content_version=$version AND content_language='$language'";
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
        return null;
    } else if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $content = $row["content_main"];

        /* Find any occurrences of <!$id!> and replace with the content_main of that ID.
        $content = preg_replace_callback(
            '/<!([a-zA-Z0-9_]+)!>/',
            function ($matches) {
                return getMainContent($matches[1]);
            },
            $content
        ); */

        echo $content;
    } else {
        return null;
    }
}


function getTitleBoxText($id, $version = 1, $language = "en")
{
    include("db_connect.php");

    $query = "SELECT content_title, content_subtitle FROM shin_content WHERE content_id='$id' AND content_version=$version AND content_language='$language'";
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
        return null;
    } else if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $title = $row["content_title"];
        $subtitle = $row["content_subtitle"];
        echo "<h1>$title</h1>";

        if ($subtitle != null) {
            echo "<h2>$subtitle</h2>";
        }
    } else {
        return null;
    }

    $query_creators = "SELECT tag FROM shin_tags WHERE content_id='$id' AND (content_version=$version OR content_version IS NULL) AND (content_language='$language' OR content_language IS NULL) AND tag_type='author'";
    $result_creators = $mysqli->query($query_creators);
    if (mysqli_num_rows($result_creators) > 0) {
        echo "<h2>By ";
        while ($row_creators = $result_creators->fetch_assoc()) {
            $creator = $row_creators["tag"];
            echo "$creator";
        }
        echo "</h2>";
    }
}


/*******************
 * ROUTE FUNCTIONS *
 *******************/

// getRoute($route_id): Take a route ID and return the decoded JSON array.
// Active route will be passed in through cookies.
function getRoute($route_id)
{
    include("db_connect.php");

    $query = "SELECT route_main FROM shin_routes WHERE route_id = '$route_id'";
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) == 0) {
        return null;
    } else if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $route = json_decode($row["route_main"], true);
        return $route;
    } else {
        return null;
    }
}


// getNeighbors($route, $id): Take a decoded JSON array, find any object where the passed id is in an object under a "current" key, and return all "previous" and "next" keys.
function getNeighbors($route, $id, $version = null)
{
    foreach ($route as $value) {
        // Check if the array has a "current" key.
        if (array_key_exists("current", $value)) {
            // Check if the "current" key has the passed ID.
            if ($value["current"]["content_id"] == $id) {
                if ($value["previous"] == null && $value["next"] != null) {
                    return array("next" => $value["next"]);
                } else if ($value["next"] != null && $value["previous"] != null) {
                    return array("previous" => $value["previous"], "next" => $value["next"]);
                } else if ($value["next"] == null && $value["previous"] != null) {
                    return array("previous" => $value["previous"]);
                } else {
                    return null;
                }
            }
        } else {
            // If not, check if the array has any children.
            if (is_array($value)) {
                // If so, run the function again.
                getNeighbors($value, $id, $version);
            }
        }
    }
}


function getFirstPage($route)
{
    foreach ($route as $value) {
        // Check if the array has a "current" key.
        if (array_key_exists("current", $value)) {
            // If so, return the "current" key.
            return $value["current"];
        } else {
            // If not, check if the array has any children.
            if (is_array($value)) {
                // If so, run the function again.
                getFirstPage($value);
            }
        }
    }
}


/************************
 * MEDIA TYPE FUNCTIONS *
 ************************/


function pluralizeTypeTag($type)
{
    include('db_connect.php');
    $type_query = "SELECT media_tag_plural FROM media_tags WHERE media_tag='$type'";
    $type_result = $mysqli->query($type_query);
    $type_row = $type_result->fetch_assoc();
    return $type_row["media_tag_plural"];
}


function getTypeChildren($type)
{
    include("db_connect.php");

    $query_children = "SELECT child_tag FROM tag_web WHERE parent_tag='$type' AND child_tag IN (SELECT DISTINCT tag FROM shin_tags) ORDER BY (SELECT COUNT(*) FROM shin_tags WHERE tag=child_tag) DESC";
    $result_children = $mysqli->query($query_children);
    if (mysqli_num_rows($result_children) > 0) {
        // Put results into an array.
        $children = [];
        while ($row_children = $result_children->fetch_assoc()) {
            $child = $row_children["child_tag"];
            array_push($children, $child);
        }

        // Loop through array and display results.
        foreach ($children as $child) {
            $query_child = "SELECT media_tag, media_tag_plural FROM media_tags WHERE media_tag='$child'";
            $result_child = $mysqli->query($query_child);
            if (mysqli_num_rows($result_child) == 0) {
                return null;
            } else if (mysqli_num_rows($result_child) == 1) {
                $row_child = $result_child->fetch_assoc();
                $child = $row_child["media_tag"];
                $child_plural = $row_child["media_tag_plural"];
                echo "<h2>$child_plural</h2>";
            }
        }
    }
}


// A function to get all the entries of a given type, but only the topmost entries (so if you have a type of "book", it will only return the books themselves, not the chapters within those books, even if the chapters also have the "book" tag).
function getEntriesOfTypeNew($type)
{
    // 1. Get ALL entries of a given type.
    // 2. Find out which ones entries in the previous query are also the children of other entries in that same query.
    // 3. Remove those entries from the query.
    $query_type = "SELECT content_id FROM shin_metadata WHERE content_id NOT IN (SELECT child_id, child_version FROM shin_web WHERE parent_id AND child_id IN (SELECT content_id, content_version, content_language FROM shin_tags WHERE tag='$type'))";
    // 4. Then get child tags. If there are any, determine which of the above entries are also of the more specific type. Display them grouped together.
    // 5. Display remainder underneath as "Misc. [plural_tag]."


    // Get all child tags, do above for each of those, then do "get from parent tag where NOT IN whatever we just got."
    // For (content_id, content_version) NOT IN: https://stackoverflow.com/a/61385628
}


/******************
 * DECK FUNCTIONS *
 ******************/


function addTableOfContents($id, $v = null, $l = null)
{
    include("db_connect.php");

    // If version is not null, only children of that version should be displayed.
    // "WHERE parent_version=$v"
    // If version is null... child links should have no version either.
    $version_conditonal = ($v != null) ? "AND parent_version=$v)" : ") AND shin_content.content_version=1";
    $language_conditional = ($l != null) ? "AND shin_content.content_language='$l'" : "AND shin_content.content_language='en'";

    if ($id == "0") {
        $query = "SELECT shin_metadata.content_id, shin_metadata.content_version, shin_metadata.content_language, shin_content.content_title, shin_content.content_snippet FROM shin_metadata JOIN shin_content ON shin_metadata.content_id=shin_content.content_id WHERE shin_metadata.content_id NOT IN (SELECT child_id FROM shin_web) ORDER BY shin_metadata.chronology, shin_content.content_title ASC";

        $result = $mysqli->query($query);
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='deck'>";
            while ($row = $result->fetch_assoc()) {
                $id = $row["content_id"];
                $version = $row["content_version"];
                $title = $row["content_title"];
                $snippet = $row["content_snippet"];
                echo buildDefaultCard($id, $version, $title, $snippet);
            }
            echo "</div>";
        }
    } else {
        $revisedDistinctQuery = "SELECT DISTINCT shin_web.child_id FROM shin_web JOIN shin_metadata ON shin_web.child_id=shin_metadata.content_id WHERE parent_id='$id' ORDER BY shin_metadata.chronology/*, shin_content.content_title*/ ASC";
        // $query = "SELECT shin_content.content_id, shin_content.content_version, shin_content.content_language, shin_content.content_title, shin_content.content_snippet FROM shin_metadata JOIN shin_content ON shin_metadata.content_id=shin_content.content_id WHERE shin_metadata.content_id IN (SELECT child_id FROM shin_web WHERE parent_id='$id' $version_conditonal $language_conditional ORDER BY shin_metadata.chronology, shin_content.content_title ASC";
        // For each ID, get all versions that are child of $id (apply version conditional), then create one button for each version.

        $result = $mysqli->query($revisedDistinctQuery);
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='deck'>";
            while ($row = $result->fetch_assoc()) {
                $childID = $row["child_id"];
                $query = "SELECT shin_content.content_id, shin_content.content_version, shin_content.version_title, shin_content.content_language, shin_content.content_title, shin_content.content_snippet FROM shin_metadata JOIN shin_content ON shin_metadata.content_id=shin_content.content_id WHERE shin_metadata.content_id IN (SELECT child_id FROM shin_web WHERE parent_id='$id' AND child_id='$childID' $version_conditonal $language_conditional ORDER BY shin_content.content_version, shin_metadata.chronology, shin_content.content_title ASC";

                $childResult = $mysqli->query($query);
                if (mysqli_num_rows($childResult) > 0) {
                    $uniqueVersions = [];
                    while ($childRow = $childResult->fetch_assoc()) {
                        array_push($uniqueVersions, [
                            "content_id" => $childRow["content_id"],
                            "content_version" => $childRow["content_version"],
                            "version_title" => $childRow["version_title"],
                            "content_language" => $childRow["content_language"],
                            "content_title" => $childRow["content_title"],
                            "content_snippet" => $childRow["content_snippet"]
                        ]);
                    }
                }
                // Put version titles into an array.
                $versionTitles = [];
                foreach ($uniqueVersions as $version) {
                    array_push($versionTitles, $version["version_title"]);
                }
                echo buildDefaultCard($childID, $uniqueVersions[0]["content_version"], $uniqueVersions[0]["content_title"], $uniqueVersions[0]["content_snippet"], true, $versionTitles);
            }
            echo "</div>";
        }
    }
}



/**
 * OKAY, SO. This function. This one right here. It's the problem.
 * What I need is one function that can take an ID, an optional single version # or an array of #s, and an optional language.
 * Then return a card. No questions asked, nothing else required, nada. It should also take an optional SIZE parameter.
 * Default medium. That's what I need to do today.
 * This function will need several helper functions.
 * 1. A function to get content metadata — title, subtitle, snippet, word_count, etc.
 * 2. A function to get "true" metadata — release date, completion_status, chronology, theme_color, etc.
 * ACTUALLY. Chronology needs to be grabbed first, so this function can be called for each set of IDs in chronological order.
 * 3. A function to get all relevant tags.
 * 4. A function to find the optimal image for the card.
 */


function buildDefaultCard($id, $v, $title, $snippet, $small = false, $versions = null)
{
    if ($v == "") {
        $card = "<a class='card medium__card' href='/read/?id=$id'>";
    } else {
        $card = "<a class='card medium__card' href='/read/?id=$id&v=$v'>";
    }

    if ($small) {
        str_replace("medium__card", "small__card", $card);
    }

    // If file exists ../img/story/contents/$id.webp, use that as the card image.
    if (file_exists("../img/story/contents/$id.webp")) {
        $card .= "<img src='/img/story/contents/$id.webp' alt='$title'>";
    }

    $uniqueVersions = "";
    // If more than one version, add a version tag.
    if ($versions != null) {
        // Implode $versions with ", "
        $uniqueVersions = "<p>" . implode(", ", $versions);
        +"</p>";
    }

    $card .= "<div class='card__text'><h3>$title</h3><div class='versions'>$uniqueVersions<p>Word Count: 980</p></div><p>$snippet</p></div>";
    $card .= "</a>";
    return $card;
}


function getTypeTags($id, $v = null) {
    include("db_connect.php");

    $query = "";
    if ($v == null) {
        $query = "SELECT tag FROM shin_tags WHERE content_id='$id' AND tag_type='type'";
    } else {
        if (is_array($v)) {
            $query = "SELECT tag FROM shin_tags WHERE content_id='$id' AND content_version IN (" . implode(",", $v) . ") AND tag_type='type'";
        } else {
            $query = "SELECT tag FROM shin_tags WHERE content_id='$id' AND content_version=$v AND tag_type='type'";
        }
    }

    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) > 0) {
        $tags = [];
        while ($row = $result->fetch_assoc()) {
            array_push($tags, $row["tag"]);
        }
        return $tags;
    } else {
        return null;
    }
}




function buildDefaultCardNuva($id, $v = null, $lang = null, $size = "medium")
{
    if ($v == null) {
        // If $v is null, check if there are actually multiple versions.
        $allV = checkForMultipleVersions($id);
        # If length of $allV is 1, $v = $allV[0]. Else, leave $v null.
        if (count($allV) == 1) {
            $v = $allV[0];
        }
    }

    if ($lang == null) {
        $potential_lang = getUserLanguage();
        $available_lang = getAvailableLanguages($id, $v);
        if (in_array($potential_lang, $available_lang)) {
            $lang = $potential_lang;
        } else {
            if (in_array("en", $available_lang)) {
                $lang = "en";
            } else {
                $lang = $available_lang[0];
            }
        }
    }

    // Generate basic HREF.
    $cardHREF = getHREF(null, $id, $v, $lang);
    // Check if there is a semantic tag for this content.
    if (!is_array($v)) {
        $semanticTag = translateToSemantic($id, $v, $lang);
        if ($semanticTag != 1) {
            $cardHREF = getHREF($semanticTag);
        }
    }

    $card = "";
    switch ($size) {
        case "small":
            $card = "<a class='card small__card' href='$cardHREF'>";
            break;
        case "medium":
            $card = "<a class='card medium__card' href='$cardHREF'>";
            break;
        case "large":
            $card = "<a class='card large__card' href='$cardHREF'>";
            break;
    }


    // Step 0: Check for videospace.
    if ($size == "large" && in_array("movie", getTypeTags($id, $v))) {
        // Find out if there's a child element with the type tag "teaser," "trailer," or "TV spot."
        $versionConditonal = versionConditional($v, true);
        $query = "SELECT child_id FROM shin_web WHERE parent_id='$id' $versionConditonal AND child_id IN (SELECT content_id FROM shin_tags WHERE tag_type='type' AND tag IN ('teaser', 'trailer', 'TV spot'))";
    }


    /**
     * 0. videospace (if movie && teaser/trailer/TV spot available)
     * 1. img
     * 2. card__text
     *    2.a. h3:title
     *    2.b. div.versions
     *       2.b.i. p:versions
     *       2.b.ii. p:release_date
     *       2.b.iii. p:word_count
     *    2.c. div.tags
     *       2.c.i. p:tag
     *    2.d. p:snippet
     */

    return $card .= "</a>";
}



function versionConditional($v, $web = null) {
    if ($web != null) {
        if ($v == null) {
            return "";
        } else {
            if (is_array($v)) {
                return "AND parent_version IN (" . implode(",", $v) . ")";
            } else {
                return "AND parent_version=$v";
                // AND (parent_version=$v OR parent_version IS NULL), maybe? How should this behave?
                // Let's say we have the German cut of the movie, with different eyes for Makuta. MOLMOV.2 or whatever. Are trailers for the English version of the movie also trailers for this version? In a strict sense, no.
                // So in a STRICT sense, "OR parent_version IS NULL" is NOT correct. Only connections where the parent_version is 2 are "genuine."
                // But in a looser sense... I mean, obviously they're still RELEVANT.
                // Also language should be especially relevant for videospaces...
            }
        }
    }

    if ($v == null) {
        return "";
    } else {
        if (is_array($v)) {
            return "AND content_version IN (" . implode(",", $v) . ")";
        } else {
            return "AND content_version=$v";
        }
    }
}



/*******************************
 * LANGUAGE FUNCTIONS I GUESS? *
 *******************************/


function getUserLanguage()
{
    $locale = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    if ($locale != null) {
        return substr($locale, 0, 2);
    } else {
        return "en";
    }
}


function getAvailableLanguages($id, $v = null)
{
    include("db_connect.php");

    // Determine which versions to check, if any.
    $query = "";
    if ($v == null) {
        $query = "SELECT DISTINCT content_language FROM shin_content WHERE content_id='$id'";
    } else {
        if (is_array($v)) {
            $query = "SELECT DISTINCT content_language FROM shin_content WHERE content_id='$id' AND content_version IN (" . implode(",", $v) . ")";
        } else {
            $query = "SELECT DISTINCT content_language FROM shin_content WHERE content_id='$id' AND content_version=$v";
        }
    }

    // Put the returned languages into an array.
    $result = $mysqli->query($query);
    $languages = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($languages, $row["content_language"]);
        }
    }
    return $languages;
}


function populateStaticGenerator($base_path, $lang)
{
    $path = getcwd();

    if ($base_path != "") {
        $path .= "/static/$base_path/$lang.html";
    } else {
        $path .= "/static/$lang.html";
    }

    return $path;
}


function populateStatic($base_path)
{
    $lang = getUserLanguage();
    $path = populateStaticGenerator($base_path, $lang);

    if (file_exists($path)) {
        echo file_get_contents($path);
    } else {
        $default_path = populateStaticGenerator($base_path, "en");
        if (file_exists($default_path)) {
            echo file_get_contents($default_path);
        } else {
            echo "<p>Missing file at " . $path . " or " . $default_path . ". Please report to admin@wallofhistory.com</p>";
        }
    }
}


/***********************
 * UNIVERSAL FUNCTIONS *
 ***********************/


// This function returns an array of results when a query returns just one row.
// Can be used for simplifying functions that require several simple queries.
function getData($column, $query)
{
    include("db_connect.php");

    $data = [];
    $result = $mysqli->query($query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($data, $row[$column]);
        }
    }
    return $data;
}


// This function attempts to find an image.
function getImages($path, $schemas = null, $id = null, $v = null, $lang = null, $caption = null)
{
    $formats = [".webp", ".jpg", ".jpeg", ".png"];
    $defaultSchemas = ["$id.$v.$lang", "$id.$v", "$id"];
    if ($schemas == null) {
        $schemas = $defaultSchemas;
    }
    foreach ($schemas as $scheme) {
        foreach ($formats as $format) {
            $image = $path . "$scheme$format";
            if (file_exists(".." . $image)) {
                return "<img src='" . $image . "' alt='" . $caption . "'>";
            }
        }
    }
    return "";
}


// This function finds the most specific OGP image available for a work.
// Perhaps there should be a "recurse up" flag? Like, *Into the Darkness* doesn't have its own OGP image, but the one for Mahri Nui is still better than the default...
function getOGPImages($id, $v, $lang)
{
    $formats = [".webp", ".jpg", ".jpeg", ".png"];
    $names = ["$id.$v.$lang", "$id.$v", "$id"];
    foreach ($names as $name) {
        foreach ($formats as $format) {
            $image = "../img/ogp/$name$format";
            if (file_exists($image)) {
                return $image;
            }
        }
    }
    return "/img/ogp2.png";
}


/***************************
 * END UNIVERSAL FUNCTIONS *
 ***************************/


/********************************
 * CONTENT POPULATION FUNCTIONS *
 * These functions populate the *
 * reader page, from top (head) *
 * to bottom (main).            *
 ********************************/


function generateHead($title, $description, $ogpImage, $themeColor, $notFound = false)
{
    echo "<meta content='$title | Wall of History' property='og:title'/>";
    echo "<meta content='$description' property='og:description'/>";
    echo "<meta content='http://www.wallofhistory.com$ogpImage' property='og:image'/>";
    echo "<meta name='theme-color' content='#$themeColor'>";
    echo "<title>$title | Wall of History</title>";

    if ($notFound) {
        echo "<meta http-equiv='Refresh' content=\"0; url='https://wallofhistory.com/404.html'\"/>";
    }
}


// This function populates the <head> of the page with content-specific OGP data.
function populateHead($id, $v = null, $lang = null)
{
    include("db_connect.php");

    $result = $mysqli->query("SELECT content_title, content_snippet, content_theme_color FROM shin_metadata JOIN shin_content ON shin_metadata.content_id = shin_content.content_id WHERE shin_metadata.content_id='$id' AND shin_content.content_version=$v AND shin_content.content_language='$lang' ORDER BY shin_metadata.chronology, shin_content.content_title ASC");
    $numRows = mysqli_num_rows($result);
    $image = getOGPImages($id, $v, $lang);

    // If query returns no result, determine whether or not user is on the table of contents, and respond accordingly.
    if ((!($id == 0)) && ($numRows == 0)) {
        generateHead("404", "Page not found.", "/img/ogp2.png", "938170", true);
    } else if ($id == 0 && $numRows == 0) {
        generateHead("Table of Contents", "Six heroes. One destiny.", "/img/ogp2.png", "938170");
    }

    // If query does return a result, respond accordingly.
    while ($row = $result->fetch_assoc()) {
        $title = strip_tags($row["content_title"]);

        // Get full title, if chapter.
        $chapterQuery = "SELECT COUNT(tag) AS tags FROM shin_tags WHERE content_id = '$id' AND tag = 'chapter'";
        $chapterCount = getData("tags", $chapterQuery);
        if ($chapterCount[0] > 0) {
            $parentQuery = "SELECT content_title FROM shin_content JOIN shin_web ON shin_web.parent_id = shin_content.content_id WHERE shin_web.child_id = '" . $id . "' LIMIT 1";
            $parent = strip_tags(getData("content_title", $parentQuery)[0]);
            $title = $title . " | " . $parent;
        }

        generateHead($title, strip_tags($row["content_snippet"]), $image, $row['content_theme_color']);
    }
}


// This function populates the head of the page with content-specific CSS links.
function populateCSS($id)
{
    include("db_connect.php");

    // Type
    $query = "SELECT tag FROM shin_tags WHERE content_id=\"$id\" AND tag_type = 'type'";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_assoc()) {
        if (file_exists("..//css/type/" . $row["tag"] . ".css")) {
            echo "<link rel='stylesheet' type='text/css' href='/css/type/" . $row["tag"] . ".css'>\n";
        }
    }

    // Grandparent (hierarchal=0)/Parent (hierarchal=1)
    // Needs version support.
    $sql = "SELECT parent_id FROM shin_web WHERE child_id ='$id' AND parent_id NOT IN (SELECT content_id FROM shin_tags WHERE tag='collection') ORDER BY hierarchal ASC;";
    $result = $mysqli->query($sql);
    $numRows = mysqli_num_rows($result);
    if ($numRows >= 1) {
        while ($row = $result->fetch_assoc()) {
            $parent_id = $row["parent_id"];
            if (file_exists("../css/type/$parent_id.css")) {
                echo "<link rel='stylesheet' type='text/css' href='/css/type/$parent_id.css'>\n";
            }
        }
    }

    // Self
    if (file_exists("../css/id/$id.css")) {
        echo "<link rel='stylesheet' type='text/css' href='/css/id/$id.css'>\n";
    }
}


// This function loads a unique header for a page, if it has one.
function loadHeader($id, $v = null, $lang = null)
{
    include("db_connect.php");

    // If $v is not null...
    $versionConditonal = ($v != null) ? "AND content_version=$v" : "";
    $languageConditional = ($lang != null) ? "AND content_language='$lang'" : "";
    $queryHeader = "SELECT header_main FROM shin_metadata JOIN shin_headers ON shin_metadata.content_header = shin_headers.header_id WHERE shin_metadata.content_id='$id' $versionConditonal $languageConditional LIMIT 1";

    // Make this recurse up to get parents if none.
    $resultHeader = $mysqli->query($queryHeader);
    $numRows = mysqli_num_rows($resultHeader);

    if ($numRows == 0) {
        echo "<img src='/img/headers/Faber-Files-Bionicle-logo-Transparent.png' alt='BIONICLE'>\n";
    } else {
        while ($rowHeader = $resultHeader->fetch_assoc()) {
            echo $rowHeader["header_main"];
        }
    }
}


/**************************
 * MAIN CONTENT FUNCTIONS *
 * (Divided due to size.) *
 **************************/


// This function gets the parent(s), if any, of the current page, and displays them.
function loadContentParents($id, $v, $title)
{
    include("db_connect.php");

    $parents_query = "SELECT * FROM story_reference_web WHERE child_id=\"$id\" AND child_version=$v";
    $parents = $mysqli->query($parents_query);
    $num_parents = mysqli_num_rows($parents);

    if ($id === "0") {
        echo "<div class='titleBoxText'><h1>Table of Contents</h1></div></section>";
    } else if ((!($id === "0")) && ($num_parents === 0)) {
        echo "<div class='titleBoxText'><h3><a onClick='location.href=\"/read/\"'>BIONICLE</a></h3>";
    } else {
        echo getImages($id, $v, "NULL", $title[0]);
        echo "<div class='titleBoxText'>";

        if ($num_parents === 1) {
            while ($row = $parents->fetch_assoc()) {
                $parent_title_query = "SELECT title FROM story_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
                $parent_title = $mysqli->query($parent_title_query);
                while ($new_row = $parent_title->fetch_assoc()) {
                    echo "<h3><a onClick=\"goTo('" . $row["parent_id"] . "." . $row["parent_version"] . "')\">" . $new_row["title"] . "</a></h3>";
                }
            }
        } else if ($num_parents > 1) {
            echo "<div class='multiparents'><button onclick='carouselBack(this)'><span class='leftarrow'></span></button>";
            while ($row = $parents->fetch_assoc()) {
                $sql_title = "SELECT title FROM story_content WHERE id=\"" . $row["parent_id"] . "\" AND content_version = \"" . $row["parent_version"] . "\"";
                // ORDER BY chronology, title ASC
                $result_title = $mysqli->query($sql_title);
                while ($row_title = $result_title->fetch_assoc()) {
                    $parentid = $row["parent_id"];
                    echo "<h3><a id='$parentid' onClick=\"goTo('$parentid." . $row["parent_version"] . "')\">" . $row_title["title"] . "</a></h3>";
                }
            }
            echo "<button onclick='carouselForward(this)'><span class='rightarrow'></span></button></div>";
        }
    }
}


function sanitizeContributors($contributors_array)
{
    $sanitized_contributors = array();
    $exploded_contributors = array();
    foreach ($contributors_array as $contributor) {
        array_push($exploded_contributors, explode(" by ", $contributor));
    }

    $contributor_types = array();
    foreach ($exploded_contributors as $contributor) {
        array_push($contributor_types, $contributor[0]);
    }

    $unique_contributor_types = array_unique($contributor_types);
    foreach ($unique_contributor_types as $type) {
        // If count > 1, get all [1] with that type.
        //     1. Append "and " to last.
        //     2. Implode with comma.
        //     3. Add to sanitized array.
        $count = 0;
        $latest = array();
        $contributors = array();
        foreach ($exploded_contributors as $contributor) {
            if ($contributor[0] === $type) {
                array_push($contributors, $contributor[1]);
                $latest = implode(" by ", $contributor);
                $count++;
            }
        }

        if ($count > 1) {
            $last = array_pop($contributors);
            $contributors = implode(", ", $contributors);
            $contributors .= " and " . $last;
            array_push($sanitized_contributors, implode(" by ", array($type, $contributors)));
        } else {
            array_push($sanitized_contributors, $latest);
        }
        // Else, pass through to sanitized array as-is.
    }

    return implode(", ", $sanitized_contributors);
}


function loadContentContributors($id)
{
    include("db_connect.php");

    $contributors_query = "SELECT detailed_tag AS tag FROM story_tags WHERE id = \"" . $id . "\" AND (tag_type = 'author')";
    $contributors = $mysqli->query($contributors_query);
    $num_rows = mysqli_num_rows($contributors);
    if ($num_rows == 1) {
        while ($row = $contributors->fetch_assoc()) {
            echo "<h3>" . $row["tag"] . "</h3>";
        }
    }

    if ($num_rows > 1) {
        echo "<h3>";
        $contributors_array = array();
        // Replace with implode.
        while ($row = $contributors->fetch_assoc()) {
            array_push($contributors_array, $row["tag"]);
        }
        sort($contributors_array);
        echo sanitizeContributors($contributors_array) . "</h3>";
    }
}


// Function to get all version titles, release date(s), word count, and... contributor tags.
function getDetails($id, $primeversion, $lang)
{
    $data_count = 0;
    $output = "<div class='versions'>\n";

    $versionquery = "SELECT version_title FROM story_content WHERE id = '$id' AND content_language = '$lang' AND version_title IS NOT NULL ORDER BY content_version ASC LIMIT 3";
    $versions = getData("version_title", $versionquery);
    if (count($versions) > 0 && count($versions) <= 2) {
        // Increment data_count.
        $data_count++;
        $output .= "<p>" . implode(", ", $versions) . "</p>\n";
    } else if (count($versions) > 2) {
        // Increment data_count.
        $data_count++;
        $output .= "<p>" . implode(", ", array_slice($versions, 0, 2)) . ", OTHERS</p>\n";
    }

    $releasequery = "SELECT publication_date FROM story_metadata WHERE id = '$id'";
    $release = getData("publication_date", $releasequery);
    if ($release[0] != "") {
        // Increment data_count.
        $data_count++;
        $output .= "<p>RELEASED " . str_replace("-", "/", implode(", ", getData("publication_date", $releasequery))) . "</p>\n";
    }

    $wordquery = "SELECT ROUND(AVG(word_count), 0) AS word_count FROM story_content WHERE id = '$id' and content_version = '$primeversion'";
    $words = getData("word_count", $wordquery);
    if ($words[0] != "") {
        // Increment data_count.
        $data_count++;
        $output .= "<p>WORD COUNT: " . implode(", ", getData("word_count", $wordquery)) . "</p>\n";
    }

    $output .= "</div>";
    if ($data_count > 0) {
        echo $output;
    }

    $tagsquery = "SELECT detailed_tag FROM story_tags WHERE id = '$id' AND (tag_type='author' OR tag_type='type')";
    $tags = getData("detailed_tag", $tagsquery);
    if (!empty($tags)) {
        echo "<div class='tags'>";
        foreach ($tags as $tag) {
            echo "<p class='tag'>" . $tag . "</p>";
        }
        echo "</div>";
    }
}
