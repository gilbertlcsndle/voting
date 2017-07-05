<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel="icon" href="logo/sjnhs.png">
  
  <title>Students</title>
  
  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
</head>
<body>
<?php session_start(); ?>
<?php  if (!empty($_SESSION) && $_SESSION['user_level'] == 'Administrator'): ?>
  <?php 
  include 'functions/filter_data.php';
  include 'functions/displayer.php';
  include 'functions/crud.php';
  
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
      'profiles.id_no,
       profiles.fname, 
       profiles.mname, 
       profiles.lname,
       profiles.gender,
       accounts.user_level,
       accounts.is_active,
       accounts.is_voted,
       students.section', 
      array('profiles', 'accounts', 'students'),
      array('accounts.id_no', 'students.id_no'),
      array('profiles.id_no', 'profiles.id_no'), 
      isset($_SESSION[$active_page]['search']) ? 
      $_SESSION[$active_page]['search'] : 
      '', 
      isset($_SESSION[$active_page]['sort']) ? 
      $_SESSION[$active_page]['sort'] : 
      '',
      array(
          'profiles.id_no',
          'profiles.fname',
          'profiles.mname',
          'profiles.lname'
      ),
      array('profiles.gender', 'students.section')
  );
  ?>

  <?php include 'navigation.php'; ?>
  
  <div class='container'>
    <?php
    $crud = new crud();

    if (isset($_POST['activate']) or isset($_POST['deactivate'])) {
        $data              = array();
        $data['is_active'] = isset($_POST['activate']) ? True : False;
        // update(data, table, where, where_value)
        if ($crud->update($data, 'accounts', 'id_no', "'$_POST[id_no]'")) {
            header('refresh:0');
        } else {
            echo "<div id='alert-danger'>
                Please check your internet connection
                and try again.
                </div>";
        }
    }

    if (isset($_POST['remove'])) {
        if ($crud->delete('profiles', 'id_no', "'$_POST[id_no]'")) {
            header('refresh:0');
        } else {
            echo "<div id='alert-danger'>
                Please check your internet connection
                and try again.
                </div>";
        }
    } 

    if (isset($_POST['reset_all_id'])) {
        $query = $conn->prepare('UPDATE accounts SET is_voted=0 WHERE is_voted=1');
        $query->execute();
        header('refresh:0');
    }
    if (isset($_POST['reset_id'])) {
        $query = $conn->prepare("UPDATE accounts SET is_voted=0 WHERE id_no='$_POST[id_no]'");
        $query->execute();
        header('refresh:0');
    }
    ?>

    <div class='row'>
      <form method='POST'>
        <div class='col-sm-3'>
          <div class='input-group'>
            <input type='text' name='search' class='form-control'
              placeholder='Type id or name'
              value='<?php
                  if (!empty($_SESSION[$active_page]['search'])) { 
                      echo $_SESSION[$active_page]['search']; 
                  } 
              ?>' maxlength='255'>
            <span class='input-group-btn'>
              <button type='submit' class='btn btn-success'>
                <span class='glyphicon glyphicon-search'></span>
              </button>
            </span>
          </div>
        </div>
        <div class='col-sm-3'>
        <?php 
        $sort_choices = array(
            'M',
            'F',
            'MakaDiyos',
            'Matulungin',
            'Maaalalahanin',
            'Mapagmahal',
            'Masipag',
            'MakaTao',
            'Maunawain',
            'Matiyaga',
            'Matapat',
            'Masikap',
            'Makakalikasin',
            'Mapagkalinga',
            'Malikhain',
            'Masinop',
            'Maagap',
            'Mapayapa',
            'Makabansa',
            'Masigasig',
            'Magiting',
            'Masunurin',
            'Matatag'
        );
        ?>
          <div class='input-group'>
            <select name='sort' class='form-control'>
              <option value=''>-- select --</option>
              <?php foreach ($sort_choices as &$value): ?>
                <option
                <?php 
                if ($value == 'M' or $value == 'F') {
                    echo "value='$value' ";
                }  
                if (
                    !empty($_SESSION[$active_page]['sort']) and 
                    $_SESSION[$active_page]['sort'] == $value
                ) {
                    echo 'selected'; 
                }
                ?>>
                  <?php 
                  if ($value == 'M') {
                      echo 'Male';
                  } elseif ($value == 'F') {
                      echo 'Female';
                  } else {
                      echo $value;
                  }
                  ?>
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
      <div class='col-sm-2'>
        <form method='POST'>
          <button type='submit' name='show_all' class='btn btn-default'>
            Show all
          </button>
        </form>
        
      </div>
      <div class="col-sm-2">
        <form action="" method="post">
          <button type="submit" class='btn btn-default' name='reset_all_id'>Reset all id</button>
        </form>
      </div>
      <div class='col-sm-2'>
        <?php $last_page = "$active_page?page=$displayer->_currentpage"; ?>
        <a href='register.php?last_page=<?php echo $last_page; ?>&manage=Student' 
          class='btn btn-success pull-right'>
          <span class='glyphicon glyphicon-plus'></span>
          New
        </a>
      </div>
    </div>

    <table class='table'>
      <thead>
        <tr>
          <th>Id</th>
          <th>Full name</th>
          <th>Gender</th>
          <th>Section</th>  
          <th>Voted</th> 
          <th>Status</th>  
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php if ($displayer->_data): ?>
        <?php foreach ($displayer->_data as &$people): ?>
          <tr>
            <td><?php echo $people['id_no']; ?></td>
            <td>
            <?php echo "$people[fname] $people[mname] $people[lname]"; ?>
            </td>
            <td>
            <?php 
            if ($people['gender'] == 'M') {
                echo 'Male'; 
            } elseif ($people['gender'] == 'F') {
                echo 'Female';
            }
            ?>
            </td>
            <td><?php echo $people['section']; ?></td>
            <td>
              <?php 
              if ($people['is_voted']) {
                  echo 'Yes';
              } else {
                  echo 'No';
              }
              ?>
            </td>
            <td>
              <?php if ($people['is_active']): ?>
                Active
              <?php else: ?>
                Not active 
              <?php endif ?>
            </td>
            <td>
              <a href="register.php?id_no=<?php 
                echo $people['id_no']; 
              ?>&last_page=<?php echo $last_page; ?>" 
                class='btn btn-info'>
                <span class='glyphicon glyphicon-pencil'></span>  
              </a>
              <form method='POST'>
                <input type='hidden' name='id_no' 
                  value='<?php echo $people['id_no']; ?>'>
                <button type='submit' name='remove' 
                  class='btn btn-danger'>
                  <span class='glyphicon glyphicon-trash'></span>
                </button>
                
                <?php if ($people['is_active']): ?>
                  <button type='submit' name='deactivate' 
                    class='btn btn-default' title='Deactivate'>
                    <span class="glyphicon glyphicon-remove"></span>
                  </button>
                <?php else: ?>
                  <button type='submit' name='activate' class='btn btn-primary'
                    title='Activate'>
                    <span class="glyphicon glyphicon-ok"></span>
                  </button>
                <?php endif; ?>
                
                <form action="" method="post">
                  <input type="hidden" name="id_no" 
                    value='<?php echo $people['id_no'] ?>'/>
                  <?php if ($people['is_voted']): ?>
                    <button type="submit" class='btn btn-success'
                      title='Reset id' name='reset_id'>
                      <span class="glyphicon glyphicon-refresh"></span>
                    </button>
                  <?php else: ?>
                    <button type="submit" class='btn btn-success' disabled>
                      <span class="glyphicon glyphicon-refresh"></span>
                    </button>
                  <?php endif ?>
                </form>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <td colspan='7'><div id='alert-info'>No results found.</div></td>
      <?php endif; ?>
      </tbody>
    </table>

    <div class='pull-right'><?php echo $displayer->_links; ?></div>
    
    <div class='pull-left clear-right' id='showperpage'>
      <?php echo $displayer->_showperpage; ?>
    </div>
    
  </div>

  <script src='js/jquery-3.1.1.min.js'></script>
  <script src='js/bootstrap.min.js'></script>
  <script src='js/alert-bootstrap.js'></script>
  <script src='js/get-student-form.js'></script>
  <script src='js/confirm-action.js'></script>
  <script>
  $(function(){
      $('[name="reset_all_id"]').click(function(e){
          var is_confirm = confirm("Are you sure you want to reset all the id?");

          if (!is_confirm) {
              e.preventDefault();
          }
      });
      $('[name="reset_id"]').click(function(e){
          var is_confirm = confirm("Are you sure you want to reset the id?");

          if (!is_confirm) {
              e.preventDefault();
          }
      });
  });
  </script>

<?php else: ?>
  <?php header('location:login.php'); ?>

<?php endif; ?>
</body>
</html>