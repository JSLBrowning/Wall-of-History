<?php


// Include Parsedown.
require("/parsedown-1.7.4/Parsedown.php");
// Include the Tree class.
require("/tree.php");


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
 * WHAT TO DO WITH IMAGES:
 * Asset folders can contain:
 * 1. OGP images (/[id/s]/ogp.png or /ogp/[id/s].png).
 * 2. Titlebox images (/[id/s]/[anything].png /assets/[id/s].png).
 *    1. Can link to 3D assets (/[id/s]/3d.txt).
 * 3. Slideshow images (/[id/s]/[int].png /assets/[id/s]/[int].png).
 */


/**
 * If no language passed, get best option. Easy.
 * If no version passed:
 *    1. If only one version exists, use that.
 *    2. If multiple versions exist, create a disambiguation page.
 */


/******************************
 * DATA TRANSLATION FUNCTIONS *
 ******************************/


/**
 * GENERAL PURPOSE
 */


// Fine, I'll do it myself.
// DELETE THIS when we transition to PHP 8.
function str_contains($haystack, $needle)
{
    return strpos($haystack, $needle) !== false;
}


// Helper function for sorting arrays of strings by length.
function sortByLength($a, $b)
{
    return strlen($b) - strlen($a);
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


// Function to translate a CONFIG ARRAY and a CONTENT ID into an array of possible asset paths (such as for images).
// Returns an array of multiple strings, each of which begins and ends with a /.
function translateToPath($config, $id, $v = 1, $lang = "en")
{
    include("db_connect.php");
    $content_path = $config["contentPath"];
    if (!is_array($v)) $v = [$v];

    /**
     * IDENTIFIERS
     */

    // First things first, get all semantic tags for the content.
    $query_tags = "SELECT tag FROM shin_tags WHERE content_id='$id' AND tag_type='semantic' AND content_version IN (" . implode(", ", $v) . ") AND content_language='$lang'";
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

    // If "[optional:v]" in $content_path, for each version number passed in, create a copy of the full path with that version number. Leave one copy of the path without a version number.
    if (strpos($content_path, "[optional:v]") !== false) {
        $new_paths = [];
        foreach ($paths as $path) {
            $new_path = str_replace("[optional:v]", "", $path);
            $new_path = str_replace("//", "/", $new_path);
            array_push($new_paths, $new_path);

            for ($i = 0; $i < count($v); $i++) {
                $new_path = str_replace("[optional:v]", $v[$i], $path);
                $new_path = str_replace("//", "/", $new_path);
                array_push($new_paths, $new_path);
            }
        }
        $paths = $new_paths;
    }

    return $paths;
}


/**
 * SHIN-SPECIFIC
 */


// Function to check if there are multiple versions of a given work, and return an array of those versions. Useful for when a function doesn't actually get a version number passed to it, but only one version exists.
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


// Function to choose the ideal version for a card when multiple are available. Tries to default to the lowest (positive/published) version.
function chooseDefaultVersion($arrayOfNumbers)
{
    // Get the lowest NON-NEGATIVE number.
    $lowest = 9999;
    foreach ($arrayOfNumbers as $number) {
        if ($number < $lowest && $number >= 1) {
            $lowest = $number;
        }
    }
    return $lowest;
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
function translateToSemantic($id, $v = null, $lang = null)
{
    include('db_connect.php');

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


// Function to translate IDENTIFIERS into an HREF.
function getHREF($s = null, $id = null, $v = null, $lang = null)
{
    if (is_array($v)) {
        $v = implode(",", $v);
    }

    if (!is_null($s)) {
        return '/read/?s=' . $s;
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


/******************************
 * CONTENT FETCHING FUNCTIONS *
 ******************************/


// Function to get title, subtitle, and snippet from a content ID. Returns an array of arrays.
// I'm sorry, where is this used??
function getContentData($id, $v = null, $lang = null)
{
    include('db_connect.php');

    // If $v is not null...
    $version_conditonal = ($v != null) ? "AND (content_version=$v OR content_version IS NULL)" : "";
    $language_conditional = ($lang != null) ? "AND (content_language='$lang' OR content_language IS NULL)" : "";

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
        echo "<div class='deck'>";

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
                $representative = getTypeRepresentative($child);
                $cardDataArray = getCardData($representative[0], $representative[1], $representative[2]);
                $newCardData = [
                    "id" => null,
                    "cardSize" => "medium",
                    "cardHREF" => "/read/?t=" . $child
                ];
                $cardDataArray = overwriteArrayElements($cardDataArray, $newCardData);
                $cardTextArray = getCardText($representative[0], $representative[1], $representative[2]);
                $newCardText = [
                    "content_title" => $child_plural,
                    "content_snippet" => "All media with the " . $child . " tag.",
                    "content_versions" => null,
                    "release_date" => null,
                    "content_words" => null
                ];
                $cardTextArray = overwriteArrayElements($cardTextArray, $newCardText);
                echo assembleDefaultCard($cardDataArray, $cardTextArray);
            }
        }
        echo "</div>";
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
        $result = $mysqli->query("SELECT shin_metadata.content_id, shin_metadata.content_version, shin_metadata.content_language, shin_metadata.release_date, shin_metadata.chronology FROM shin_metadata JOIN shin_content ON shin_metadata.content_id=shin_content.content_id WHERE shin_metadata.content_id NOT IN (SELECT child_id FROM shin_web) ORDER BY shin_metadata.chronology, shin_metadata.release_date, shin_content.content_title ASC");

        if (mysqli_num_rows($result) > 0) {
            $deck = [];
            while ($row = $result->fetch_assoc()) {
                $contentID = $row["content_id"];
                $contentVersion = $row["content_version"];
                $contentLanguage = $row["content_language"];
                $contentTags = getTypeTags($contentID, $contentVersion);
                $contentReleaseDate = $row["release_date"];
                $contentChronology = $row["chronology"];

                // Get parents. If in array, add as child.
                // Get children. If in array, MOVE to children of current node.

                $newNode = new Tree($contentID, $contentVersion, $contentLanguage, $contentTags, $contentReleaseDate, $contentChronology);
                array_push($deck, $newNode);
            }
            echo "<div class='deck'>";

            // Loop through array and display results.
            foreach($deck as $node) {
                // If has children, display this node as a grouping, then put all children inside.
                $cardDataArray = getCardData($node->get_id(), $node->get_versions());
                $cardTextArray = getCardText($node->get_id(), $node->get_versions());
                echo assembleDefaultCard($cardDataArray, $cardTextArray);
            }

            echo "</div>";
        }
    } else {
        $result = $mysqli->query("SELECT shin_web.child_id, GROUP_CONCAT(DISTINCT(shin_web.child_version) ORDER BY shin_web.child_version) AS child_versions FROM shin_web JOIN shin_metadata ON shin_web.child_id=shin_metadata.content_id WHERE parent_id='$id' AND (parent_version=$v OR parent_version IS NULL) GROUP BY shin_web.child_id ORDER BY shin_metadata.chronology, shin_metadata.release_date ASC");

        // For each ID, get all versions that are child of $id (apply version conditional), then create one button for each version.
        if (mysqli_num_rows($result) > 0) {
            $cardSize = "";
            echo "<div class='deck'>";
            while ($row = $result->fetch_assoc()) {
                $childID = $row["child_id"];
                $childVersion = $row["child_versions"];

                if ($cardSize == "") {
                    $cardSize = "medium";
                    $chapterResult = $mysqli->query("SELECT tag FROM shin_tags WHERE content_id='$childID' AND tag='chapter'");
                    $chapterCount = mysqli_num_rows($chapterResult);
                    if ($chapterCount > 0) {
                        $cardSize = "small";
                    }
                }

                $cardDataArray = getCardData($childID, $childVersion, null, $cardSize);
                $cardTextArray = getCardText($childID, $childVersion, null);
                echo assembleDefaultCard($cardDataArray, $cardTextArray);
            }
            echo "</div>";
        }
    }
}


// Function to get an array of all the type tags for a given work.
function getTypeTags($id, $v = null)
{
    include("db_connect.php");

    $query = "";
    if ($v == null) {
        $query = "SELECT tag FROM shin_tags WHERE content_id='$id' AND tag_type='type'";
    } else {
        if (is_array($v)) {
            $query = "SELECT tag FROM shin_tags WHERE content_id='$id' AND content_version IN (" . implode(",", $v) . ") AND tag_type='type'";
        } else {
            $query = "SELECT tag FROM shin_tags WHERE content_id='$id' AND (content_version=$v OR content_version IS NULL) AND tag_type='type'";
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


function checkForTags($id, $v, $tag)
{
    $tags = getTypeTags($id, $v);
    if ($tags != null) {
        if (in_array($tag, $tags)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function getCardText($id, $v = null, $lang = null)
{
    include("db_connect.php");
    // Three possible scenarios: $v is null (bad), $v is a single value (good), or $v is already an array (confusing, but we can fix it).
    // Determine version to proceed with.
    if ($v == null) {
        $allV = checkForMultipleVersions($id);
        $v = [chooseDefaultVersion($allV)];
    } else if (!is_array($v)) {
        if (str_contains($v, ",")) {
            $v = explode(",", $v);
        } else {
            // If $v is a single value, turn it into an array.
            $v = [$v];
        }
    }
    $defaultVersion = chooseDefaultVersion($v);

    // Determine language to proceed with.
    if ($lang == null) {
        $potential_lang = getUserLanguage();
        $available_lang = getAvailableLanguages($id, $v);
        if (in_array($potential_lang, $available_lang)) {
            $lang = $potential_lang;
        } else {
            if (in_array("en", $available_lang) || $available_lang == null) {
                $lang = "en";
            } else {
                $lang = $available_lang[0];
            }
        }
    }

    $content_title = $content_snippet = $content_words = $release_date = "";
    $content_versions = [];

    $result = $mysqli->query("SELECT shin_metadata.content_version, version_title, content_title, content_snippet, content_words, release_date FROM shin_content JOIN shin_metadata ON shin_content.content_id=shin_metadata.content_id WHERE shin_content.content_id='$id' AND (shin_content.content_version IN (" . implode(",", $v) . ") OR shin_content.content_version IS NULL) AND (shin_content.content_language='$lang' OR shin_content.content_language IS NULL) ORDER BY shin_metadata.chronology ASC");
    while ($row = $result->fetch_assoc()) {
        if ($row["content_version"] == $defaultVersion || $row["content_version"] == null) {
            $content_title = $row["content_title"];
            $content_snippet = $row["content_snippet"];
            $content_words = getWordCount($id, $row["content_version"], $lang);
            $release_date = "";
            if ($row["release_date"] != null) {
                $release_date = "<p>Released " . date("Y/m/d", strtotime($row["release_date"])) . "</p>";
            } else {
                $release_date = "";
            }
        }
        array_push($content_versions, $row["version_title"]);
    }

    return [
        "content_title" => $content_title,
        "content_snippet" => $content_snippet,
        "content_versions" => implode(", ", $content_versions),
        "release_date" => $release_date,
        "content_words" => $content_words
    ];
}


function getCardData($id, $v = null, $lang = null, $cardSize = "medium")
{
    include("db_connect.php");

    // Determine version to proceed with.
    if ($v == null) {
        $allV = checkForMultipleVersions($id);
        $v = [chooseDefaultVersion($allV)];
    } else if (!is_array($v)) {
        if (str_contains($v, ",")) {
            $v = explode(",", $v);
        } else {
            // If $v is a single value, turn it into an array.
            $v = [$v];
        }
    }

    // Determine language to proceed with.
    if ($lang == null) {
        $potential_lang = getUserLanguage();
        $available_lang = getAvailableLanguages($id, $v);
        if (in_array($potential_lang, $available_lang)) {
            $lang = $potential_lang;
        } else {
            if (in_array("en", $available_lang) || $available_lang == null) {
                $lang = "en";
            } else {
                $lang = $available_lang[0];
            }
        }
    }

    // Generate basic HREF.
    $cardHREF = getHREF(null, $id, chooseDefaultVersion($v), $lang);
    // Check if there is a semantic tag for this content.
    if (!is_array($v)) {
        $semanticTag = translateToSemantic($id, $v, $lang);
        if ($semanticTag != 1) {
            $cardHREF = getHREF($semanticTag);
        }
    }

    // Step 1: Image (REVISE)
    $cardImage = "";
    if ($cardSize != "small") {
        if (file_exists("../img/story/contents/$id.webp")) {
            $cardImage = "<img src='/img/story/contents/$id.webp' alt='[PUT TITLE HERE EVENTUALLY.]'>";
        }
    }

    return [
        "id" => $id,
        "v" => $v,
        "lang" => $lang,
        "cardSize" => $cardSize,
        "cardHREF" => $cardHREF,
        "img" => $cardImage
    ];
}


function assembleDefaultCard($cardDataArray, $cardTextArray)
{
    include("db_connect.php");

    $id = $cardDataArray["id"];
    $v = $cardDataArray["v"];
    // $lang = $cardDataArray["lang"];
    $cardsize = $cardDataArray["cardSize"];
    $cardHREF = $cardDataArray["cardHREF"];
    $img = $cardDataArray["img"];
    $title = $cardTextArray["content_title"];
    $versions = $cardTextArray["content_versions"];
    $releaseDate = $cardTextArray["release_date"];
    // $wordCount = $cardTextArray["wordCount"];
    $snippet = $cardTextArray["content_snippet"];

    $card = "";
    switch ($cardsize) {
        case "small":
            $card = "<a class='card small__card' href='$cardHREF'>";
            $title = "<div class='chapter__header'><h4>$title</h4></div>";
            $releaseDate = $snippet = "";
            break;
        case "medium":
            $card = "<a class='card medium__card' href='$cardHREF'>";
            $title = "<h3>$title</h3>";
            break;
        case "large":
            $card = "<a class='card large__card' href='$cardHREF'>";
            $title = "<h3>$title</h3>";
            break;
    }


    // Step 0: Check for videospace.
    if ($cardsize == "large" && $id != null && in_array("movie", getTypeTags($id, $v))) {
        // Find out if there's a child element with the type tag "teaser," "trailer," or "TV spot."
        $versionConditonal = versionConditional($v, true);
        $result = $mysqli->query("SELECT child_id FROM shin_web WHERE parent_id='$id' $versionConditonal AND child_id IN (SELECT content_id FROM shin_tags WHERE tag_type='type' AND tag IN ('teaser', 'trailer', 'TV spot'))");
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();
            $childID = $row["child_id"];
            echo "Working on it! childID: " . $childID;
        }
    }

    $card .= "$img<div class='card__text'>$title<div class='versions'><p>$versions</p>$releaseDate</div><p>$snippet</p></div>";
    return $card .= "</a>";
}


function versionConditional($v, $web = null)
{
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

    if ($v == null) {
        $allV = checkForMultipleVersions($id);
        $v = [chooseDefaultVersion($allV)];
    }

    // Determine language to proceed with.
    if ($lang == null) {
        $potential_lang = getUserLanguage();
        $available_lang = getAvailableLanguages($id, $v);
        if (in_array($potential_lang, $available_lang)) {
            $lang = $potential_lang;
        } else {
            if (in_array("en", $available_lang) || $available_lang == null) {
                $lang = "en";
            } else {
                $lang = $available_lang[0];
            }
        }
    }

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
function loadContentParents($id, $v)
{
    include("db_connect.php");

    $parents = $mysqli->query("SELECT * FROM shin_web WHERE child_id=\"$id\" AND child_version=$v");
    // Update this function to get parent of matching language if it exists, optimal language if not.
    $numParents = mysqli_num_rows($parents);

    if ($id === "0") {
        echo "<h1>The BIONICLE Story</h1>";
        // Update this to get content_title of mainWork.
    } else if ((!($id === "0")) && ($numParents === 0)) {
        echo "<h2><a onClick='location.href=\"/read/\"'>The BIONICLE Story</a></h2>";
    } else {
        // echo getImages($id, $v, "NULL", $title[0]);

        if ($numParents === 1) {
            while ($row = $parents->fetch_assoc()) {
                $parentID = $row["parent_id"];
                $parentVersion = $row["parent_version"];
                $parentTitle = $mysqli->query("SELECT content_title FROM shin_content WHERE content_id='$parentID' AND content_version=$parentVersion LIMIT 1");
                while ($newRow = $parentTitle->fetch_assoc()) {
                    $title = $newRow["content_title"];
                    echo "<h2><a href='/read/?id=$parentID&v=$parentVersion'>$title</a></h2>";
                }
            }
        } else if ($numParents > 1) {
            echo "<div class='multiparents'><h3 onclick='carouselBack(this)'><i class='fa-solid fa-left-long'></i></h2>";
            while ($row = $parents->fetch_assoc()) {
                $parentID = $row["parent_id"];
                $parentVersion = $row["parent_version"];
                $parentTitles = $mysqli->query("SELECT content_title FROM shin_content WHERE content_id='$parentID' AND content_version=$parentVersion LIMIT 1");
                // ORDER BY chronology, title ASC
                while ($newRow = $parentTitles->fetch_assoc()) {
                    $title = $newRow["content_title"];
                    echo "<h2><a href='/read/?id=$parentID&v=$parentVersion'>$title</a></h2>";
                }
            }
            echo "<h3 onclick='carouselForward(this)'><i class='fa-solid fa-right-long'></i></h2></div>";
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


function translateRoles($role)
{
    switch ($role) {
        case "developer":
            return "Developed by ";
            break;
        case "writer":
            return "Written by ";
            break;
        case "producer":
            return "Produced by ";
            break;
        case "animator":
            return "Animated by ";
            break;
        case "illustrator":
            return "Illustrated by ";
            break;
        case "publisher":
            return "Published by ";
            break;
    }
}


function loadContentContributors($id, $v, $lang)
{
    include("db_connect.php");

    $result = $mysqli->query("SELECT creators.creator_id, creator_name, creator_role FROM creators JOIN creator_roles ON creators.creator_id=creator_roles.creator_id WHERE creator_roles.content_id='$id' AND (creator_roles.content_version=$v OR creator_roles.content_version IS NULL) AND (creator_roles.content_language='$lang' OR creator_roles.content_language IS NULL)");
    $numRows = mysqli_num_rows($result);

    if ($numRows == 1) {
        while ($row = $result->fetch_assoc()) {
            $attribution = translateRoles($row["creator_role"]) . $row["creator_name"];
            echo "<h3>$attribution</h3>";
        }
    }

    if ($numRows > 1) {
        $creatorsArray = array();
        while ($row = $result->fetch_assoc()) {
            array_push($creatorsArray, translateRoles($row["creator_role"]) . $row["creator_name"]);
        }
        sort($creatorsArray);
        $attributions = sanitizeContributors($creatorsArray);
        echo "<h3>$attributions</h3>";
    }
}


function loadTags($id, $v = null)
{
    include("db_connect.php");

    $result = $mysqli->query("SELECT tag FROM shin_tags WHERE content_id='$id' AND (content_version=$v OR content_version IS NULL) AND tag_type='type'");
    $numRows = mysqli_num_rows($result);

    if ($numRows > 0) {
        echo "<section class='title__box__tags'>";

        $tagsArray = array();
        while ($row = $result->fetch_assoc()) {
            array_push($tagsArray, $row["tag"]);
        }
        sort($tagsArray);

        foreach ($tagsArray as $tag) {
            echo "<a href='/read/?t=$tag'><i class='fa-solid fa-hashtag'></i> $tag</a>";
        }

        echo "</section>";
    }
}


function getTitleBoxText($id, $v = 1, $lang = "en")
{
    include("db_connect.php");

    // Get parents.
    loadContentParents($id, $v);

    // Get title and subtitle.
    $result = $mysqli->query("SELECT content_title, content_subtitle FROM shin_content WHERE content_id='$id' AND content_version=$v AND content_language='$lang'");
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

    // Get creators.
    loadContentContributors($id, $v, $lang);
}


function getContentAutomaticallyByType($id, $version = 1, $language = "en", $types = null)
{
    if ($types == null) {
        return null;
    }

    $contentPaths = translateToPath($GLOBALS['config'], $id, $version, $language);

    if (in_array("video", $types) || in_array("teaser", $types) || in_array("commercial", $types)) {
        foreach ($contentPaths as $path) {
            if (glob($_SERVER['DOCUMENT_ROOT'] . $path . "*.mp4") != null) {
                $filename = glob($_SERVER['DOCUMENT_ROOT'] . $path . "*.mp4")[0];
                // Remove 'F:/Wall of History/root' from the beginning of the path.
                $src = str_replace($_SERVER['DOCUMENT_ROOT'], "", $filename);
                $content = "<video controls><source src='$src' type='video/mp4'></video>";
                return $content;
            }
        }
    } else if (in_array("comic", $types)) {
        $content = "";
        // Sort content paths by length, longest to shortest. But don't return that.
        usort($contentPaths, 'sortByLength');

        $images = [];
        foreach ($contentPaths as $path) {
            $extensions = ["webp", "jpg", "jpeg", "png"];
            foreach ($extensions as $extension) {
                $imagesOnPath = glob($_SERVER['DOCUMENT_ROOT'] . $path . "*.$extension");
                // If there is not an image already in $images with the same name, add it.
                foreach ($imagesOnPath as $image) {
                    $image = str_replace($_SERVER['DOCUMENT_ROOT'], "", $image);
                    $imageMinusExtension = str_replace("." . $extension, "", $image);
                    // Check if any image in the array uses $imageMinusExtension as a key.
                    $imageAlreadyExists = false;
                    if (array_key_exists($imageMinusExtension, $images)) {
                        $imageAlreadyExists = true;
                    }

                    if (!$imageAlreadyExists) {
                        // Add the image to the array, using the filename minus the extension as the key.
                        $images[$imageMinusExtension] = $image;
                    }
                }
            }
        }

        // Natsort the array.
        natsort($images);

        if (count($images) > 0) {
            $content .= "<div class='mediaplayer'><div class='mediaplayercontents'>";
            foreach ($images as $image) {
                $content .= "<img src='$image'>";
            }
            $content .= "</div><div class='mediaplayercontrols'><button class='mediaplayerbutton' onclick='backNav(this)' style='display: none;'>‹</button><div class='slidelocationdiv'><p class='slidelocation'>1 / " . count($images) . "</p></div><button class='mediaplayerbutton' onclick='forwardNav(this)'>›</button></div></div>";
            return $content;
        } else {
            return null;
        }
    } else {
        return null;
    }
}


function getMainContent($id, $v = 1, $lang = "en")
{
    include("db_connect.php");

    $result = $mysqli->query("SELECT content_main FROM shin_content WHERE content_id='$id' AND content_version=$v AND content_language='$lang'");

    if (mysqli_num_rows($result) == 0) {
        return null;
    } else if (mysqli_num_rows($result) == 1) {
        $row = $result->fetch_assoc();
        $content = $row["content_main"];
        if ($content == null) {
            $content = getContentAutomaticallyByType($id, $v, $lang, getTypeTags($id, $v));
        } else {
            // If less than 50% of the lines in the content become with a "<", assume it's Markdown.
            $lines = explode("\n", $content);
            $linesThatAreHTML = 0;
            foreach ($lines as $line) {
                if (str_contains($line, "<") && str_contains($line, ">")) {
                    $linesThatAreHTML++;
                }
            }

            if ($linesThatAreHTML < (count($lines) / 2)) {
                $Parsedown = new Parsedown();
                $content = $Parsedown->text($content);
            }

            /* Find any occurrences of <!$id!> and replace with the content_main of that ID.
            $content = preg_replace_callback(
                '/<!([a-zA-Z0-9_]+)!>/',
                function ($matches) {
                    return getMainContent($matches[1]);
                    // Need to choose default version and language automatically, if none passed.
                },
                $content
            ); */
        }
        echo $content;
    } else {
        return null;
    }
}


/***********************
 * TYPE PAGE FUNCTIONS *
 ***********************/


function getTypeRepresentative($type)
{
    include('db_connect.php');

    // How to account for null?
    $query = "SELECT shin_tags.content_id, shin_tags.content_version, shin_tags.content_language, shin_metadata.release_date, shin_metadata.chronology FROM shin_tags JOIN shin_metadata ON shin_tags.content_id=shin_metadata.content_id WHERE tag_type='type' AND tag='$type' AND release_date IS NOT NULL ORDER BY release_date, chronology ASC LIMIT 1;";
    // Add relative chronology values to *Chronicles* novels.
    $result = $mysqli->query($query);

    if (mysqli_num_rows($result) == 1) {
        // Return the content ID, version, and language.
        $row = $result->fetch_assoc();
        return [$row["content_id"], $row["content_version"], $row["content_language"]];
    } else {
        return null;
    }
}
