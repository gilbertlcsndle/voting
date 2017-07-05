<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel="icon" href="logo/sjnhs.png">

  <title>Nominees</title>

  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
</head>
<body>
<?php session_start(); ?>
<?php  if (!empty($_SESSION) && $_SESSION['user_level'] == 'Administrator'): ?>
  <?php
  require 'functions/filter_data.php';
  require 'functions/displayer.php';
  require 'functions/crud.php';

  $active_page = basename($_SERVER['PHP_SELF']);

  // sets the data table
  $displayer = new displayer();

  $displayer->_limit       = isset($_COOKIE['perPage'][$active_page]) ?
                             $_COOKIE['perPage'][$active_page] :
                             5;
  $displayer->_targetpage  = $active_page;
  $displayer->_stages      = 3;
  $displayer->_currentpage = !empty($_GET['page']) ? $_GET['page'] : 1;

  $displayer->data(
      'pk, fname, mname, lname, category, photo',
      'candidates',
      '',
      '',
      !empty($_SESSION[$active_page]['search']) ? 
      $_SESSION[$active_page]['search'] : 
      '', 
      !empty($_SESSION[$active_page]['sort']) ? 
      $_SESSION[$active_page]['sort'] : 
      '',
      array(
        'fname', 
        'mname', 
        'lname'
      ),
      'category'
  );
  
  $crud = new crud();
  ?>

  <?php require 'navigation.php'; ?>

  <div class='container'>
    <div class='row'>
      <div class='col-sm-4'>
        <div class='panel panel-success'>
          <div class='panel-heading text-center'>
            <?php
            echo isset($_SESSION[$active_page]['edit']['pk']) ? 
            'Update candidate' : 
            'New candidate'; 
            ?>
          </div>
          <div class='panel-body'>
            <?php require 'forms/form-candidates.php'; ?>
          </div>
        </div>
      </div>
      <div class='col-sm-8'>
      <?php
      if (isset($_POST['edit'])) {
          $_SESSION[$active_page]['edit']['pk'] = $_POST['pk'];
          echo "<script>
              location.replace(
                  '$active_page?page=$displayer->_currentpage'
              );
              </script>";
      }

      if (isset($_POST['remove'])) {
          if ($crud->delete('candidates', 'pk', "'$_POST[pk]'")) {
              echo "<script>
              location.replace(
                  '$active_page?page=$displayer->_currentpage'
              );
              </script>";
          } else {
              echo "<div id='alert-danger'>
                  Please check your internet connection and
                  try again.
                  </div>";
          }
      }

      ?>
        <div class='row'>
          <form method='POST'>
            <div class='col-md-4'>
              <div class='input-group'>
                <input type='text' name='search' class='form-control'
                  placeholder='Type name'
                  value='<?php
                      if (!empty($_SESSION[$active_page]['search'])) { 
                          echo $_SESSION[$active_page]['search'];
                      } 
                  ?>'>
                <span class='input-group-btn'>
                  <button type='submit' class='btn btn-default'>
                    <span class='glyphicon glyphicon-search'></span>
                  </button>
                </span>
              </div>
            </div>
            <div class='col-md-4'>
              <div class='input-group'>
                <select name='sort' class='form-control'
                  <?php if (!$categories) { echo 'disabled'; } ?>>
                  <option value=''>-- select --</option>
                  <?php foreach ($categories as &$value): ?>
                      <option
                        <?php 
                        if (
                            !empty($_SESSION[$active_page]['sort']) and 
                            $_SESSION[$active_page]['sort'] == $value['name']
                        ) {
                            echo 'selected';
                        }
                        ?>>
                        <?php echo $value['name']; ?>
                      </option>
                  <?php endforeach; ?>
                </select>
                <span class='input-group-btn'>
                  <button type='submit' class='btn btn-default'>
                    <span class='glyphicon glyphicon-filter'></span>
                    Filter
                  </button>
                </span>
              </div>
            </div>
          </form>

          <div class='col-md-4'>
            <form method='POST'>
              <button type='submit' name='show_all' class='btn btn-default'>
                Show all
              </button>
            </form>
          </div>
        </div>
        
        <table class='table'>
          <thead>
            <tr>
              <th>Photo</th>
              <th>Candidate</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          if ($displayer->_data): 
          ?>
            <?php foreach ($displayer->_data as &$candidate): ?>
              <tr>
                <td>
                  <img src='candidates/<?php 
                    if (!empty($candidate['photo'])) {
                        echo $candidate['photo'];
                    } else {
                        echo 'default.png';
                    }
                    ?>' width='50'>
                </td>
                <td>
                <?php 
                echo "$candidate[fname] $candidate[mname] $candidate[lname]"; 
                ?>
                </td>
                <td><?php echo $candidate['category']; ?></td>
                <td>
                  <form method='POST'>
                    <input type='hidden' name='pk' 
                      value='<?php echo $candidate['pk']; ?>'>
                    <button type='submit' name='edit' 
                      class='btn btn-info'>
                      <span class='glyphicon glyphicon-pencil'></span>  
                    </button>
                    <button type='submit' name='remove'
                      class='btn btn-danger'>
                      <span class='glyphicon glyphicon-trash'></span>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan='4'><div id='alert-info'>No results found.</div></td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>

        <div class='pull-right'><?php echo $displayer->_links; ?></div>

        <div class='clearfix'></div>
        
        <div class='pull-left'>
          <?php echo $displayer->_showperpage; ?>
        </div>
      </div>
    </div>
  </div>

  <script src='js/jquery-3.1.1.min.js'></script>
  <script src='js/bootstrap.min.js'></script>
  <script src='js/preview-photo.js'></script>
  <script src='js/confirm-action.js'></script>
  <script src='js/alert-bootstrap.js'></script>
<?php else: ?>
  <?php header('location:login.php'); ?>

<?php endif; ?>
</body>
</html>