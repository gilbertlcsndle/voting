<?php 
    $account_data = !empty($account) ? true : false;
    $manage       = !empty($_GET['manage']) ? $_GET['manage'] : ''; 
?>

<div class='form-group'>
  <label for='id-no'>User id:</label><br>
  <div class='row'>
    <div class='col-sm-6'>
      <input type='text' name='profile[id_no]'
        value="<?php if ($account_data) { echo $profile['id_no']; } ?>"
        placeholder='Your id no' class='form-control' tabindex='8' id='id-no' required>
    </div>
  </div>
</div>

<div class='clearfix'></div>

<?php if (!empty($_SESSION) and $_SESSION['user_level'] == 'Administrator'): ?>
  <div class='form-group'>
    <?php
    $user_level_choices = array('Administrator', 'Student');
    ?>
      <label for='user-level'>Level:</label><br>
    <div class='row'>
      <div class='col-sm-6'>
        <select name='account[user_level]' required class='form-control'
          id='user-level'  tabindex='9'>
          <option value=''>-- select --</option>
          <?php foreach ($user_level_choices as &$value): ?>
            <option <?php
                if ($account_data and $account['user_level'] == $value or $manage == $value) {
                    echo 'selected'; 
                } 
            ?>>
              <?php echo $value; ?>  
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class='clearfix'></div>

<div class='form-group'>
    <label for='password'>
      <?php echo (!$is_update_form) ? 'Password:' : 'Change password' ?>
    </label><br>
    <div class='col-sm-6 no-gutters'>
      <div class='input-group'>
        <input type='password' name='account[password]' 
          placeholder='<?php echo (!$is_update_form) ? 'Password' : 'New password' ?>' 
          class='form-control' id='password' tabindex='10'>
        <span class='input-group-btn' id='show-pass'>
          <button type='button' class='btn btn-default'>
            <span class='glyphicon glyphicon-eye-open'></span>
          </button>
        </span>
      </div>
    </div>
    <div class='col-sm-6 no-gutters'>
      <div class='input-group'>
        <input type='password' name='account[confirm_password]' 
          placeholder='<?php echo (!$is_update_form) ? 'Password' : 'Confirm new password' ?>' 
          class='form-control' id='password' tabindex='11'>
        <span class='input-group-btn' id='show-pass'>
          <button type='button' class='btn btn-default'>
            <span class='glyphicon glyphicon-eye-open'></span>
          </button>
        </span>
      </div>
    </div>
</div>