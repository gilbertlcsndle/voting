<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel="icon" href="logo/sjnhs.png">

  <title>Register</title>
  
  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
</head>
<body>
<?php 
session_start(); 
if (!empty($_SESSION)) {
    include 'navigation.php';
}
include 'functions/crud.php';
$crud = new crud();
?>

<?php if (empty($_SESSION)): ?>
<br>
<?php endif; ?>

<div class='container'>
  <div class='panel panel-primary'>
    <div class='panel-heading'>
      <?php echo isset($_GET['id_no']) ? 'Update people' : 'Register'; ?>
    </div>
    <div class='panel-body'>
    <?php
    // requires js/get-student-form.js not in register.php

    $profile = $account = $student = array();

    $is_update_form=false;

    if (isset($_GET['id_no'])) {
        // get(table, show, where, value)
        $profile = $crud->get('profiles', 'all', 'id_no', "'$_GET[id_no]'");
        $account = $crud->get('accounts', 'all', 'id_no', "'$_GET[id_no]'");
        if ($account['user_level'] == 'Student') {
            $student = $crud->get('students', 'all', 'id_no', "'$_GET[id_no]'");
        } else {
            $student = array();
        }
        $is_update_form=true;
    }

    include 'functions/save.php';
    include 'functions/update.php';
    ?>

      <form method='POST'>
        <div class='col-md-6'>
        <?php include 'forms/profile-form.php'; ?>
          <div id='student-form'
            data-student='<?php echo json_encode($student); ?>'>
          </div>
          <?php
          if (empty($_SESSION)) {
              include 'forms/student-form.php';
          }
          ?>
        </div>
        <div class='col-md-6'>
        <?php include 'forms/account-form.php'; ?>
        </div>
        <div class='pull-right' style='margin-top:15px;'>
          <button type='submit' 
            name="<?php echo $is_update_form ? 'update' : 'save'; ?>"
            class='btn btn-primary'>
          <?php echo $is_update_form ? 'Update' : 'Save'; ?>
          </button>
          <button type='reset' class='btn btn-default'>Reset</button>
          <?php 
          $cancel_link = '';
          if (empty($_SESSION)) {
              $cancel_link = 'login.php';
          } else {
              $cancel_link = $_GET['last_page'];
          }
          if (!empty($cancel_link)) {
            echo "<a href='$cancel_link' class='btn-link'>Cancel</a>";
          }
          ?>
          
        </div>
      </form>
    </div>
  </div>
</div>
<script src='js/jquery-3.1.1.min.js'></script>
<script src='js/bootstrap.min.js'></script>
<script src='js/get-student-form.js'></script>
<script src='js/alert-bootstrap.js'></script>
<script src='js/confirm-action.js'></script>
<script src='js/show-pass.js'></script>
</body>
</html>