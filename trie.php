<?php
include_once 'trienode.php';

class Trie {
    public $head;
    public $filename;

    function __construct($filename) {
        $this->$filename = $filename;
        $this->$head = new TrieNode();
    }

    function get_test() {
    }
}
?>
