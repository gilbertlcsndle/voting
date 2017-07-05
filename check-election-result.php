<?php
include 'functions/crud.php';
$crud = new crud();

$categories = $crud->read('categories', 'name');

$candidate_votes = array();
foreach ($categories as &$category) {
    $candidates = 
        $crud->read('candidates', 'votes', 'category', "'$category[name]'");

    if ($candidates) {
        foreach ($candidates as &$candidate) {
            array_push($candidate_votes, $candidate['votes']);
        }
    }
}
echo json_encode($candidate_votes);
?>