<?php

class Tree {
    // Properties
    public $id;
    public $versions;
    public $language;
    public $parents;
    public $children;
    public $tags;
    public $releaseDate;
    public $chronology;

    // Methods
    function __construct($id, $versions, $language, $tags, $releaseDate, $chronology) {
        $this->id = $id;
        $this->versions = $versions;
        $this->language = $language;
        $this->parents = [];
        $this->children = [];
        $this->tags = $tags;
        $this->releaseDate = $releaseDate;
        $this->chronology = $chronology;
    }

    function add_parent($parent) {
        array_push($this->parents, $parent);
    }

    function add_parents($parents) {
        foreach ($parents as $parent) {
            array_push($this->parents, $parent);
        }
    }

    function add_child($child) {
        array_push($this->children, $child);
    }

    function get_id() {
        return $this->id;
    }

    function get_versions() {
        return $this->versions;
    }

    function get_language() {
        return $this->language;
    }

    function get_parents() {
        return $this->parents;
    }

    function get_children() {
        return $this->children;
    }

    function get_tags() {
        return $this->tags;
    }

    function get_chronology() {
        return $this->chronology;
    }

    function get_releaseDate() {
        return $this->releaseDate;
    }
}

?>