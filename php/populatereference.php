<?php
function populateTitle($subject) {
    echo "$subject | Wall of History";
}


function populateReferenceSubjects() {
    echo "<ul>subjects</ul>";
}


function populateReferenceContent($subject) {
    if ($subject == null) {
        populateReferenceSubjects();
    } else {
        echo "<h1>$subject</h1>";
    }
}