<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'> 
  <link rel="icon" href="logo/sjnhs.png">

  <title>Categories</title>

  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
</head>
<body>
<?php session_start(); ?>
<?php  if (!empty($_SESSION) && $_SESSION['user_level'] == 'Administrator'): ?>
  <?php
  include 'navigation.php';
  include 'functions/crud.php';
  include 'functions/displayer.php';
  include 'functions/filter_data.php';

  $active_page = basename($_SERVER['PHP_SELF']);

  $displayer = new displayer();

  $displayer->_limit       = isset($_COOKIE['perPage'][$active_page]) ? 
      $_COOKIE['perPage'][$active_page] : 
      5;
  $currentpage             = !empty($_GET['page']) ? $_GET['page'] : 1;
  $displayer->_targetpage  = $active_page;
  $displayer->_stages      = 3;
  $displayer->_currentpage = $currentpage;

  $displayer->data(
      'all',
      'categories',
      '',
      '',
      !empty($_SESSION[$active_page]['search']) ? 
      $_SESSION[$active_page]['search'] : 
      '', 
      '',
      'name',
      ''
  );
  
  $crud = new crud();

  if (isset($_POST['registered_redirect'])) {
      $_SESSION['manage-candidates.php']['sort'] = $_POST['category'];
      header('location:manage-candidates.php');
  }
  ?>

  <div class='container'>
    <div class='row'>
      <div class='col-sm-4'>
        <div class='panel panel-success'>
          <div class='panel-heading text-center'>
          <?php 
          echo isset($_SESSION[$active_page]['edit']['name']) ? 
          'Update category' : 
          'New category'; 
          ?>
          </div>
          <div class='panel-body'>
          <?php include 'forms/form-category.php'; ?>
          </div>
        </div>
      </div>
      <div class='col-sm-8'>
      <?php    
      if (isset($_POST['edit'])) {
          $_SESSION[$active_page]['edit']['name'] = $_POST['name'];
          header('refresh:0');
      }

      if (isset($_POST['remove'])):
      ?>
        <?php if ($crud->delete('categories', 'name', "'$_POST[name]'")): ?>
          <?php header('refresh:0'); ?>
        <?php else: ?>
          <div id='alert-danger'>
          Please check your internet connection or<br>
          check if there's a 
          <form action="" method="post">
            <input type="hidden" name="category" value="<?php echo $_POST['name']; ?>" />
            <button type="submit" class="alert-link" name='registered_redirect'>
              candidate registered
            </button>
          </form> in this category.
          </div>
        <?php endif; ?>
      <?php endif; ?>
        <div class='row'>
          <div class='col-md-4'>
            <form method='POST'>  
              <div class='input-group'>
                <input type='text' name='search' class='form-control'
                  placeholder='Type name'
                  value='<?php
                      if (!empty($_SESSION[$active_page]['search'])) {
                          echo $_SESSION[$active_page]['search']; 
                      } 
                  ?>' maxlength='255'>
                <span class='input-group-btn'>
                  <button type='submit' class='btn btn-default'>
                    <span class='glyphicon glyphicon-search'></span>
                  </button>
                </span>
              </div>
            </form>
          </div>
          <div class='col-md-8'>
            <form method='POST'>
              <button type='submit' name='show_all'
                class='btn btn-default'>Show all</button>
            </form>
          </div>
        </div>
        
        <table class='table'>
          <thead>
            <tr>
              <th>Name</th>
              <th>Number of Winner(s)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($displayer->_data): ?>
            <?php foreach($displayer->_data as &$category): ?>
              <tr>
                <td><?php echo $category['name']; ?></td>
                <td><?php echo $category['number_of_candidates']; ?></td>
                <td>
                  <form method='POST'>
                    <input type='hidden' name='name'
                      value='<?php echo $category['name']; ?>'>
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
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td colspan='3'>
                <div id='alert-info'>No results found.</div>
              </td>
            </tr>
          <?php endif ?>
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
  <script src='js/alert-bootstrap.js'></script>
  <script src='js/confirm-action.js'></script>
<?php else: ?>
  <?php header('location:login.php'); ?>

<?php endif; ?>
</body>
</html>