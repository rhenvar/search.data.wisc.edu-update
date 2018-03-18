<?php
include_once 'trie.php';
include_once 'trienode.php';

$mc = new Memcached();
$mc->addServer('127.0.0.1', 11211);

$stored_trie = $mc->get('stored_trie');

$trie = unserialize($stored_trie);
$trie_node = $trie->$trie_node;
$trie_node->test();

?>
