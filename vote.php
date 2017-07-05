<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>

  <title>Vote</title>

  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
  <style>
  label {
    cursor: pointer;
  }
  .style1 {color: #FFFFFF}
  </style>
</head>
<body>
<?php session_start(); ?> 
<?php  if (!empty($_SESSION) && $_SESSION['user_level'] == 'Student'): ?>
  <?php
  include 'navigation.php'; 
  include 'functions/crud.php';
  // $conn included from crud.php

  $crud = new crud();
  
  $categories = $crud->read(
      'categories',
      'all',
      '',
      '',
      'date_created'
  );
  ?>

  <div class='container'>
    <div class='row'>
      <?php
      if (isset($_POST['vote'])) {
          include 'functions/save_vote.php';
      }

      $crud2 = new crud();

      ?>
        
      <form method='POST'>  

        <?php for ($x=0; $x < count($categories); $x++): ?>
          <?php 
          $category = $categories[$x];

          $candidates = $crud2->read(
              'candidates',
              'all',
              'category',
              "'$category[name]'"
          );
          ?>
          <?php 
          // only show choices if it is not lesser than 
          // the number of candidate to be voted
          if (
              $candidates and 
              count($candidates) >= $category['number_of_candidates']
          ): 
          ?>
            <div class='col-sm-4' id='candidate-collumn'>
              <div class='panel panel-success'>
                <div class='panel-heading'>
                <h5><span class="style1"><?php echo $category['name']; //category ?></span></h5>
                </div>
                <div class='panel-body'>
                  <?php if (!isset($_POST['vote'])): ?>
                    <div id='alert-info'>
                      Choose
                      <?php echo $category['number_of_candidates']; ?>
                    </div>
                  <?php endif; ?>
                  <?php 
                  if (
                      isset($error[$category['name']]) and isset($_POST['vote'])
                  ): 
                  ?>
                    <div id='alert-warning'>
                      <?php echo $error[$category['name']] ?>
                    </div>
                  <?php endif; ?>

                  <input type='checkbox' 
                    name='vote[<?php echo $category['name']; ?>]' value='' 
                    checked hidden>
                  <label id='candidate'
                    data-max='<?php echo $category['number_of_candidates']; ?>'>
                    

                      <?php foreach ($candidates as &$candidate): ?>
                        <?php
                        $is_checked=false;

                        if (!empty($_POST['vote'][$category['name']])) {
                            foreach (
                                $_POST['vote'][$category['name']] as 
                                &$voted_candidate
                            ) {
                                if (
                                    $voted_candidate['pk'] ==  
                                    $candidate['pk']
                                ) {
                                    $is_checked=true;
                                }
                            }
                        }
                        ?>
                  <div class='form-group'>
                          <img src='candidates/<?php
                              echo $candidate['photo']; ?>' width='150'>
              <label class='checkbox-inline'>
                          <strong>  <input type='checkbox' 
                              name='vote[<?php echo $category['name']; ?>][][pk]' 
                              value='<?php echo $candidate['pk']; ?>'
                              id='<?php echo $category['name']; ?>'
                              <?php if ($is_checked) { echo 'checked';  } ?>> </strong>
                            <?php   
                            echo 
                                "$candidate[fname] 
                                 $candidate[mname] 
                                 $candidate[lname]"; 
                            ?>
                    </label>
                  </div>  
                      <?php endforeach; ?>
                  </label>   
                </div>
              </div>
            </div>
          <?php endif; ?>
        <?php endfor; ?>
          <div class='col-xs-12'>
            <div class='pull-right'>
              <button type='submit' class='btn btn-primary' id='submit_vote'>
                Submit
              </button>
              <button type='reset' class='btn btn-default'>Reset</button>
            </div>
          </div>
        </form>
      </div>
  </div>

  <script src='js/jquery-3.1.1.min.js'></script>
  <script src='js/bootstrap.min.js'></script>
  <script src='js/confirm-action.js'></script>
  <script>
  $('div#candidate-collumn').each(function(index, obj){
      for (var x=0; x < $('div#candidate-collumn').length; x+=3) {
          if (index !== 0) {
              if (index == x) {
                  $(obj).css('clear','left');
              }
          }
      }
  });

  $(function(){
      $("[type='checkbox']").click(function(event){
          if (
              $(this).parents('label#candidate').find('input:checked').length >
              $(this).parents('label#candidate').data('max')
          ) {      
              event.preventDefault();
          }
      });

      // confirm if the vote will be submitted or not
      $('#submit_vote').click(function(e){
          var is_confirm = confirm("Are you sure you want to submit your vote?");
          
          if (!is_confirm) {
              e.preventDefault();
          }
      });
  });  
  </script>
  <script src='js/alert-bootstrap.js'></script>

<?php else: ?>
  <?php header('location:login.php'); ?>
  
<?php endif; ?>
</body>
</html>