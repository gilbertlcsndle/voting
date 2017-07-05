<?php
$is_update_form = isset($_SESSION[$active_page]['edit']['pk']) ? true : false;

// gets the data for update form
$candidate = array();
if ($is_update_form) {
    $candidate = $crud->get(
        'candidates', 
        'all', 
        'pk',
        "'".$_SESSION[$active_page]['edit']['pk']."'"
    );
}

if (isset($_POST['save'])) {
    $candidate = $_POST['candidate'];
    // checks candidate if already registered in other category

    if ($_FILES['photo']['error'] == UPLOAD_ERR_NO_FILE) {
        $candidate['photo'] = 'default.png';
        $is_valid           = true;
    } else {
        if (
            move_uploaded_file(
                $_FILES['photo']['tmp_name'], 
                'candidates/'.$_FILES['photo']['name']
            )
        ) {
            $candidate['photo'] = $_FILES['photo']['name'];
            $is_valid = true;
        } else {
            echo "<div id='alert-danger'>
                There was an error while uploading the photo.<br>
                Please check your internet connection or 
                choose a different photo.</div>";
            $candidate['photo'] = 'default.png';
            $is_valid = false;
        }
    }

    if ($is_valid) { 
        if ($crud->create('candidates', $candidate)) {
            // resets the form
            $candidate = array();
            echo "<script>
                location.replace('$active_page?page=$displayer->_currentpage');
                </script>";
        } else {
            echo "<div id='alert-danger'>
                Please check your internet connection and
                try again.</div>";
        }
    }
}

if (isset($_POST['update'])) {
    $candidate = $_POST['candidate'];

    if ($_FILES['photo']['error'] == UPLOAD_ERR_NO_FILE) {
        $candidate['photo'] = $_POST['old-photo'];
        $is_valid = true;
    } else {
        if (
            move_uploaded_file(
                $_FILES['photo']['tmp_name'], 
                'candidates/'.$_FILES['photo']['name']
            )
        ) {
            $candidate['photo'] = $_FILES['photo']['name'];
            $is_valid = true;
        } else {
            $candidate['photo'] = $_POST['old-photo'];
            echo "<div id='alert-danger'><br>
                There was an error while uploading the photo.<br>
                Please check your internet connection or 
                choose a different photo.</div>";
            $is_valid = false;
        }
    }
    if ($is_valid) {
        if (
            $crud->update(
                $candidate, 
                'candidates', 
                'pk', 
                "'".$_SESSION[$active_page]['edit']['pk']."'"
            )
        ) {
            $candidate = array();
            unset($_SESSION[$active_page]['edit']['pk']);
            echo "<script>
                location.replace('$active_page?page=$displayer->_currentpage');
                </script>";
        } else {
            echo "<div id='alert-danger'>
                Please check your internet connection and try again.
                </div>";
        }
    }
}

if (isset($_POST['cancel'])) {
    unset($_SESSION[$active_page]['edit']['pk']);
    echo "<script>
        location.replace('$active_page?page=$displayer->_currentpage');
        </script>";
}
?>
<form method='POST' enctype='multipart/form-data'>
  <div class='form-group row text-center'>
    <input type='hidden' name='old-photo' 
      value='<?php if (!empty($candidate)) { echo $candidate['photo']; } ?>'>
    <input type='file' accept='image/*' name='photo' id='upload-photo' 
      style='display:none;'>
    <img src='candidates/<?php 
      echo (!empty($candidate)) ? $candidate['photo'] : 'default.png'; ?>'
      width='100' id='preview-photo' alt='Candidate photo'>
    <br>
    <label for='upload-photo' class='btn btn-default'>
      <span class='glyphicon glyphicon-open'></span>
      Upload
    </label>
  </div>
  <div class='form-group row'>
    <label for='full-name' class='col-sm-4'>Full name:</label>
    <div class='col-sm-8'>
      <input type='text' name='candidate[fname]'
        placeholder='First name' class='form-control'
        value='<?php if (!empty($candidate)) { echo $candidate['fname']; }?>'
        id='full-name' maxlength='255' required>
      <input type='text' name='candidate[mname]'
        placeholder='Middle name' class='form-control'
        value='<?php if (!empty($candidate)) { echo $candidate['mname']; }?>'
        maxlength='255' required>
      <input type='text' name='candidate[lname]'
        placeholder='Last name' class='form-control'
        value='<?php if (!empty($candidate)) { echo $candidate['lname']; }?>'
        maxlength='255' required>
    </div>
  </div>

  <?php $crud2 = new crud(); ?>
  <div class='form-group row'>
    <label for='category' class='col-sm-4'>Category:</label>
    <div class='col-sm-8'>  
      <select name='candidate[category]' required
        <?php
        if (!$categories = $crud2->read('categories')) { echo 'disabled'; } 
        ?> class='form-control' id='category'>
        
        <option value=''>-- select --</option>
        <?php if (!empty($categories)): ?>
          <?php for ($x=0; $x < count($categories); $x++): ?>
            <?php $category = $categories[$x]; ?>
            <?php 
            $is_selected = False;
            if (!empty($candidate) and 
                $category['name'] == $candidate['category']) {
                $is_selected = True;
            } ?>
            <option <?php if ($is_selected) { echo 'selected'; } ?>>
              <?php echo $category['name']; ?>
            </option>
          <?php endfor; ?>
        <?php endif; ?>
      </select>
    </div>
  </div>
  <div class='pull-right'>
    <button type='submit' 
      name='<?php echo $is_update_form ? 'update' : 'save'; ?>'
      class='btn btn-primary'>
      <?php echo $is_update_form ? 'Update' : 'Save'; ?>
    </button>
    <button type='reset' class='btn btn-default'>Reset</button>
</form>
  <?php if ($is_update_form): ?>
    <form method='POST'>
      <button type='submit' name='cancel' class='btn-link'>Cancel</button>
    </form>  
  <?php endif; ?>
  </div>