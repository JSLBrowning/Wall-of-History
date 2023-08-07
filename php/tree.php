<?php

class Tree {
    // Properties
    public $id;
    public $version;
    public $language;
    public $children;
    public $tags;
    public $releaseDate;
    public $chronology;

    // Methods
    function __construct($id, $version, $language, $tags, $releaseDate, $chronology) {
        $this->id = $id;
        $this->version = $version;
        $this->language = $language;
        $this->children = [];
        $this->tags = $tags;
        $this->releaseDate = $releaseDate;
        $this->chronology = $chronology;
    }

    function add_child($child) {
        array_push($this->children, $child);
    }

    function get_id() {
        return $this->id;
    }

    function get_version() {
        return $this->version;
    }

    function get_language() {
        return $this->language;
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