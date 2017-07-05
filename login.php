<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>

  <title>Login</title>
  
  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
  <link rel="icon" href="logo/sjnhs.png">
<style>
/*adjust the size in different devices*/
/*xs*/
.login-form {
  width: 60%;
  margin-top: 10%;
}
/*sm*/
@media (min-width: 768px) {
  .login-form {
    width: 50%;
  }   
}
/*md*/
@media (min-width: 992px) {
  .login-form {
    width: 30%;
  }   
}
body {
  background: url('logo/eto.jpg');
  background-size:100%;
   background-repeat:no-repeat;
   padding-top: 90px;
}
.style1 {font-size: 20px}
.style2 {font-size: 18px}
.style3 {font-family: 'Glyphicons Halflings'}
</style>
</head>
<body>
<?php require_once 'functions/create_admin.php'; ?>
<div class="col-lg-4 col-lg-offset-8">
  <div class='panel panel-success'>
    <div class='panel-heading'>
      <div align="left"><b class='panel-title'>
        <span class="style1">Login</span><span class="style1"></span></b> </div>
    </div>
    <div class='panel-body'>
      <?php
      if (isset($_POST['login'])) {
          $login = $_POST['login'];
          require_once 'functions/crud.php';
          require_once 'functions/encrypt-decrypt.php';
          $crud = new crud();

          $user_account = $crud->join(
              'profiles.id_no,
               profiles.fname,
               profiles.lname, 
               accounts.is_active, 
               accounts.user_level,
               accounts.is_voted,
               accounts.password',
              array('accounts', 'profiles'),
              array('accounts.id_no'),
              array('profiles.id_no'),
              array('profiles.id_no'),
              array("'$login[id_no]'")
          );

          if (
              $user_account and
              decrypt_pass($login['password'], $user_account[0]['password'])
          ) {
              if ($user_account[0]['is_active']) {
                  session_start();
                  if ($user_account[0]['user_level'] == 'Administrator') {
                      $_SESSION = $user_account[0];

                      header('location:index.php');
                  } elseif ($user_account[0]['user_level'] == 'Student') {

                      if ($user_account[0]['is_voted']) {
                          echo "<div id='alert-info'>
                              This account was already voted.
                              </div>";
                      } else {
                          $_SESSION = $user_account[0];
                          header('location:vote.php');
                      }

                  } else {
                      session_destroy();
                      echo "<div id='alert-danger'>
                          This account is invalid.</div>";
                  }

              } else {
                  echo "<div id='alert-warning'>
                      Your account is not yet activated.<br>
                      Please refer to the administrator to activate it.
                      </div>";
              }
          } else {
              echo "<div id='alert-warning'>Invalid ID or password</div>";
          }
      }
      ?>
      <form method='POST' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
        <div class='form-group'>
          <input type='text' name='login[id_no]' placeholder='User id'
            class='form-control' 
            value='<?php 
                if (isset($_POST['login'])) { echo $_POST['login']['id_no']; } 
            ?>' maxlength='255' required>
        </div>
        <div class='form-group'>
          <div class='input-group'>
            <input type='password' name='login[password]' placeholder='Password' 
              class='form-control' required>
            <span class='input-group-btn' id='show-pass'>
              <button type='button' class='btn btn-default'>
                <span class='glyphicon glyphicon-eye-open'></span>
              </button>
            </span>
          </div>
        </div>
        <button type='submit' class='btn btn-success'>Log in</button><br>
        Not registered yet? <a href='register.php'>Register here!</a>
      </form>
    </div>  
  </div>
</div>
<script src='js/jquery-3.1.1.min.js'></script>
<script src='js/alert-bootstrap.js'></script>
<script src='js/show-pass.js'></script>
</body>
</html>