<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>

  <title>Change password</title>

  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
</head>
<body>
<?php session_start(); ?>
<?php if ($_SESSION['user_level'] == 'Administrator'): ?>
  <?php require 'navigation.php';  ?>
  <div class='container'>
    <div id='change-password' 
      class='panel panel-primary center-block'>
  
      <div class='panel-heading'>Change password</div>
      
      <div class='panel-body'>
      <?php require 'functions/update-password.php'; ?>
        <form method='POST'>
          <div class='form-group row'>
            <label for='old-password' class='col-sm-4'>
              Old password:
            </label>
            <div class='col-sm-8'>
              <div class='input-group'>  
                <input type='password' name='account[old_password]' 
                  placeholder='Your old password'
                  class='form-control' id='old-password' 
                  tabindex='1' required>
                <span class='input-group-btn' id='show-pass'>
                  <button type='button' class='btn btn-default'>
                  <span class='glyphicon glyphicon-eye-open'></span>
                  </button>
                </span>
              </div>
            </div>
          </div>
          <div class='form-group row'>
            <label for='new-password' class='col-sm-4'>
              New password:
            </label>
            <div class='col-sm-8'>
              <div class='input-group'>  
                <input type='password' name='account[new_password]' 
                  placeholder='Your new password' 
                  class='form-control' id='new-password' 
                  tabindex='2' required>
                <span class='input-group-btn' id='show-pass'>
                  <button type='button' class='btn btn-default'>
                  <span class='glyphicon glyphicon-eye-open'></span>
                  </button>
                </span>
              </div>
            </div>
          </div>
          <div class='form-group row'>
            <label for='confirm-password' class='col-sm-4'>
              Confirm new password:
            </label>
            <div class='col-sm-8'>
              <div class='input-group'>  
                <input type='password' name='account[confirm_password]'
                  placeholder='Confirm new password'
                  class='form-control' id='confirm-password' 
                  tabindex='3' required>
                <span class='input-group-btn' id='show-pass'>
                  <button type='button' class='btn btn-default'>
                  <span class='glyphicon glyphicon-eye-open'></span>
                  </button>
                </span>
              </div>
            </div>
          </div>
          <div class='pull-right'>
            <button type='submit' class='btn btn-primary'>Submit</button>
            <button type='reset' class='btn btn-default'>Reset</button>
          </div>
        </form>
      </div>

    </div>
  
  </div>

  <script src='js/jquery-3.1.1.min.js'></script>
  <script src='js/bootstrap.min.js'></script>
  <script src='js/alert-bootstrap.js'></script>
  <script src='js/confirm-action.js'></script>
  <script src='js/show-pass.js'></script>
<?php else:  ?>
  <?php header('location:login.php'); ?>

<?php endif; ?>
</body>
</html>