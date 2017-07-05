<?php
if (isset($_SESSION[$active_page]['edit']['name'])) {
    $category = $crud->get(
        'categories', 
        'all', 
        'name',
        "'".$_SESSION[$active_page]['edit']['name']."'"
    );
}

date_default_timezone_set('Asia/Manila');
?>
<?php
if (isset($_POST['save'])) {
    $category = $_POST['category'];
    $category['date_created'] = date('Y-m-d H:i:s');

    // create(table, data)
    if ($crud->create('categories', $category)) {
        $category = array();
        echo "<script>
            location.replace('$active_page?page=$displayer->_currentpage');
            </script>";
    } else {
        echo "<div id='alert-danger'>
            Please check your internet connection or<br>
            check if the category is not yet on the list.</div>";
    }
}

if (isset($_POST['update'])) {
    $category = $_POST['category'];
    $category['date_created'] = date('Y-m-d H:i:s');

    if (
        $crud->update(
            $category, 
            'categories', 
            'name', 
            "'".$_SESSION[$active_page]['edit']['name']."'"
        )
    ) {
        $category = array();
        unset($_SESSION[$active_page]['edit']['name']);
        echo "<script>
            location.replace('$active_page?page=$displayer->_currentpage');
            </script>";
    } else {
        echo "<div id='alert-danger'>
            Please check your internet connection or<br>
            check if the category is not yet on the list.</div>";
    }
}

if (isset($_POST['cancel'])) {
    unset($_SESSION[$active_page]['edit']['name']);
    echo "<script>
        location.replace('$active_page?page=$displayer->_currentpage');
        </script>";
}

?>
<form method='POST'>
  <div class='form-group row'>
    <label for='name' class='col-sm-4'>Category:</label>
    <div class='col-sm-8'>
      <input type='text' name='category[name]'
        value='<?php if (!empty($category)) { echo $category['name']; } ?>' 
        placeholder='Name' class='form-control' id='name' 
        maxlength='255' required> 
    </div>
  </div>
  <div class='form-group row'>
    <label for='number-of-candidates' class='col-sm-4'>Number of Winner(s):</label>
    <div class='col-sm-8'>
      <input type='number' name='category[number_of_candidates]'
        value='<?php
               if (!empty($category)) { echo $category['number_of_candidates']; }
               ?>'
        placeholder='Quantity' class='form-control' id='number-of-candidates'
        max='2147483647' required>
    </div>
  </div>
  <div class='pull-right'>
    <button type='submit' 
      name='<?php 
      echo isset($_SESSION[$active_page]['edit']['name']) ? 'update' : 'save'; 
      ?>'
      class='btn btn-primary'>
      <?php 
      echo isset($_SESSION[$active_page]['edit']['name']) ? 'Update' : 'Save'; 
      ?>
    </button>
    <button type='reset' class='btn btn-default'>Reset</button>
</form>
<?php if (isset($_SESSION[$active_page]['edit']['name'])): ?>
    <form method='POST'>
      <button type='submit' name='cancel' class='btn-link'>Cancel</button>
    </form>
<?php endif; ?>
  </div> <!-- class pull-right -->