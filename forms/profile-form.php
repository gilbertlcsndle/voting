<?php 
$profile_data = !empty($profile) ? True : False;

$is_male = $is_female = False;
if ($profile_data and $profile['gender'] == 'M') {
    $is_male   = True;
} elseif ($profile_data and $profile['gender'] == 'F') {
    $is_female = True;
}
?>

<div class='form-group'> 
  <label for='full-name'>Full name:</label><br>
  <div class='col-sm-4 no-gutters'>
    <input type='text' name='profile[fname]'
      value="<?php if ($profile_data) { echo $profile['fname']; } ?>" 
      placeholder='First name' class='form-control'
      id='full-name' maxlength='255' tabindex='1' required>
  </div>
  <div class='col-sm-4 no-gutters'>
    <input type='text' name='profile[mname]' 
      value="<?php if ($profile_data) { echo $profile['mname']; } ?>" 
      placeholder='Middle name' class='form-control' tabindex='2' maxlength='255' required>
  </div>
  <div class='col-sm-4 no-gutters'>
    <input type='text' name='profile[lname]' 
      value="<?php if ($profile_data) { echo $profile['lname']; } ?>" 
      placeholder='Last name' class='form-control' tabindex='3' maxlength='255' required>
  </div>
</div>
<div class='form-group' style='margin-top: 50px;'>
  <label for='gender'>Gender:</label><br>
    <label class='radio-inline'>
      <input type='radio' name='profile[gender]' 
        value='M' id='gender' 
         tabindex='4' required <?php if ($is_male) { echo 'checked'; } ?>>Male
    </label>
    <label class='radio-inline'>
      <input type='radio' name='profile[gender]' 
        value='F' <?php if ($is_female) { echo 'checked'; } ?>  tabindex='5' required>Female
    </label>
</div>