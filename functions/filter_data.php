<?php
include 'functions/filter.php';
$filter = new filter(basename($_SERVER['PHP_SELF']));

// sets the search and sort session variables for filtering
if (isset($_POST['search']) or isset($_POST['sort'])) {
    $filter->set_filter($_POST);
}
// resets the filters
if (isset($_POST['show_all'])) {
    $filter->unset_filter(array('search', 'sort'));
}
?>