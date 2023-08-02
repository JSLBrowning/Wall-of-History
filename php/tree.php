<?php

class Tree {
    // Properties
    public $id;
    public $version;
    public $language;
    public $children;

    // Methods
    function __construct($id, $version, $language, $children) {
        $this->id = $id;
        $this->version = $version;
        $this->language = $language;
        $this->children = $children;
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
}

?>