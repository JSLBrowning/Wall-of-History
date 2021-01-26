<?php
    function populateHead($id) {
        // This function is pretty straightforward — it populates the head of the page with content-specific OGP data.
        include("..//php/db_connect.php");

        // Idea: Recurse UP web to get OGP image, recurse DOWN to get chronology for table of contents.
        // https://www.mysqltutorial.org/mysql-recursive-cte/

        $sql = "SELECT title, snippet, IFNULL(large_image, (SELECT large_image FROM woh_web JOIN woh_metadata ON woh_web.parent_id = woh_metadata.id WHERE woh_web.child_id = \"" . $id . "\" LIMIT 1)) FROM woh_metadata WHERE woh_metadata.id = \"" . $id . "\"";

        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            if ($row["large_image"] === NULL) {
                echo "<meta content='" . strip_tags($row["title"]) . " | Wall of History' property='og:title'/>
                    <meta content='" . $row["snippet"] . " | Wall of History' property='og:description'/>
                    <meta content='http://www.wallofhistory.co/img/ogp.png' property='og:image'/>
                    <meta content='summary_large_image' name='twitter:card'/>
                    <meta content='@Wall_of_History' name='twitter:site'/>
                    <title>" . strip_tags($row["title"]) . " | Wall of History</title>";
            } else {
                echo "<meta content='" . strip_tags($row["title"]) . " | Wall of History' property='og:title'/>
                    <meta content='" . $row["snippet"] . " | Wall of History' property='og:description'/>
                    <meta content='http://www.wallofhistory.co/img/ogp.png' property='og:image'/>
                    <meta content='" . $row["large_image"] . "' property='og:image'/>
                    <meta content='summary_large_image' name='twitter:card'/>
                    <meta content='@Wall_of_History' name='twitter:site'/>
                    <title>" . strip_tags($row["title"]) . " | Wall of History</title>";
            }
        }
    }

    function hasChildren($id) {
        // This function finds any and all children that a given piece of content has, then echoes them in a list format.
        include("..//php/db_connect.php");

        $sql = "SELECT child_id AS cid, title, chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = \"" . $id . "\" ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
        /* Okay, it works, but it's not elegant — the downward recursion (@ IFNULL) for the chronology values only works for one level. Should try to replace that with true recursion. */

        $result = $mysqli->query($sql);
        $num_rows = mysqli_num_rows($result);

        // If the content doesn't have any children (chapter, etc.), this function will return nothing, and no children will be displayed to the user.
        if ($num_rows != 0) {
            // If the content does have children, they will be displayed in a list.
            echo "<ol id='sortable'>";
            
            // Each child must be made its own list item…
            while ($row = $result->fetch_assoc()) {
                // …which must have the matching title, of course.
                $sql_title = "SELECT title FROM woh_metadata WHERE woh_metadata.id = \"" . $row["cid"] . "\"";
                
                $result_title = $mysqli->query($sql_title);
                
                while($row_title = $result_title->fetch_assoc()){
                    echo "<li><a href='/read/?id=" . $row["cid"] . "'>" . $row_title["title"] . " ↗</a>" . "</li>";
                }
            }
            echo "</ol>";
        }
    }

    function loadHeader($id) {
        // TO-DO: Make this load the header and echo it into the empty header space.
        // Delete header space if none?
    }

    function loadContent($id) {
        // This function is the most complicated, echoing the actuals contents of the page from the top (parent(s), title, author) down (content).
        include("..//php/db_connect.php");

        // GET PARENT(S), IF ANY, AND DISPLAY AT THE TOP OF <MAIN>
        $sql = "SELECT * FROM woh_web WHERE child_id = \"" . $id . "\"";
        
        $result = $mysqli->query($sql);
        $num_rows = mysqli_num_rows($result);
        while ($row = $result->fetch_assoc()) {
            if ($num_rows == 0) {

            } elseif ($num_rows == 1) {
                $sql_title = "SELECT title FROM woh_metadata WHERE id = \"" . $row["parent_id"] . "\"";
                $result_title = $mysqli->query($sql_title);
                while($row_title = $result_title->fetch_assoc()){
                    echo "<h2><a onClick='location.href=\"/read/?id=" . $row["parent_id"] . "\"'>" . $row_title["title"] . "</a>" . "</h2>";
                }
            } else {
                echo "<h2>↑</h2>";
            }
        }

        // GET AND DISPLAY TITLE
        $sql = "SELECT title FROM woh_metadata WHERE id = \"" . $id . "\"";

        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<h1>" . $row["title"] . "</h1>";
        }

        // GET AND DISPLAY CONTRIBUTORS
        $sql = "SELECT tag FROM woh_tags WHERE id = \"" . $id . "\" AND (tag_type = 'developer' OR tag_type = 'author' OR tag_type = 'illustrator')";

        $result = $mysqli->query($sql);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows == 0) {

        } elseif ($num_rows == 1) {
            while ($row = $result->fetch_assoc()) {
                echo "<h2>" . $row["tag"] . "</h2>";
            }
        } else {
            echo "<h2>";
            $num_commas = $num_rows - 1;
            while ($row = $result->fetch_assoc()) {
                echo $row["tag"];
                if ($num_commas > 0) {
                    echo ", ";
                    $num_commas--;
                }
            }
            echo "</h2>";
        }

        // Might as well fetch all the content for the content in question, right?
        $sql = "SELECT * FROM woh_content WHERE id = \"" . $id . "\"";

        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            // Self-explanatory — the main column contains the contents of the main tag.
            echo $row["main"];
        }

        hasChildren($id);
    }

    function populateSettings() {
        // This function generates the list of contents seen on the settings page. Once that's done, it's up to the JavaScript to give that list functionality.
        include("..//php/db_connect.php");

        $sql = "SELECT child_id AS cid, title, chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE child_id NOT IN (SELECT parent_id FROM woh_web) ORDER BY IFNULL(chronology, (SELECT chronology FROM woh_web JOIN woh_metadata ON woh_web.child_id = woh_metadata.id WHERE woh_web.parent_id = cid ORDER BY chronology LIMIT 1)), title ASC";
        
        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            $sql_chapter = "SELECT tag FROM woh_tags WHERE (id = '" . $row["cid"] . "' AND tag = 'chapter')";

            $result_chapter = $mysqli->query($sql_chapter);
            if ($result_chapter === false) {
                echo "<p>" . $row["title"] . "</p>";
            } else {
                $sql_title = "SELECT title FROM woh_metadata JOIN woh_web ON woh_web.parent_id = woh_metadata.id WHERE woh_web.child_id = '" . $row["cid"] . "'";

                $result_title = $mysqli->query($sql_title);
                while ($row_title = $result_title->fetch_assoc()) {
                    echo "<p>" . $row_title["title"] . ": " . $row["title"] . "</p>";
                }
            }
        }
    }
?>