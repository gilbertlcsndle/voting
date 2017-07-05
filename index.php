<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>

  <title>Result</title>

  <link href='css/bootstrap.min.css' rel='stylesheet'>
  <link href='css/custom.css' rel='stylesheet'>
   <link rel="icon" href="logo/sjnhs.png">
</head>
<body>
<?php session_start(); ?>
<?php  if (!empty($_SESSION) && $_SESSION['user_level'] == 'Administrator'): ?>
<?php include 'navigation.php'; ?>
<?php
if (isset($_POST['reset_result'])) {
    include 'functions/connect.php';

    $qry = $conn->prepare('UPDATE candidates SET votes=0 WHERE votes!=0');
    $qry->execute();
    header('refresh:0');
} 
?>
  <div class='container'>
    <div class="row">
      <div class="col-sm-6">
        <a href='print.php' target="_blank" class="btn btn-primary">
          <span class="glyphicon glyphicon-print"></span>
          Print
        </a>
      </div>
      <div class="col-sm-6">
        <form action="" method="post">
          <button type="submit" class="btn btn-default pull-right"
            name='reset_result'>Reset result</button>
        </form>
      </div>
    </div>
    <div class='row'>
      <div id='collumn-navigation' class='form-inline hidden-xs'>
        <label for='collumn'>Collumns:</label>
          <select name='collumn' class='form-control' id='collumn'>
            <option value='4'>3</option>
            <option value='6'>2</option>
            <option value='12'>1</option>
          </select>
        </label>
      </div>	  
      <div id='election-result'>
      <?php
      include 'functions/crud.php';

      $crud = new crud();
      
      $categories = $crud->read(
          'categories',
          'all',
          '',
          '',
          'date_created'
      );

      $crud2 = new crud();
      
      $candidate_votes = array(); // initial 
      ?>
      <?php for ($x=0; $x < count($categories); $x++): ?>
        <?php
        $category = $categories[$x];
        
        $candidates = $crud2->read(
            'candidates',
            'all',
            'category',
            "'$category[name]'"
        );

        $votes = $crud2->read(
            'candidates',
            'SUM(votes) AS all_votes',
            'category',
            "'$category[name]'"    
        );
        ?>

        <?php 
        // only show category if it is not lesser than 
        // the number of candidate to be voted
        if (
            $candidates and 
            count($candidates) >= $category['number_of_candidates']
        ): 
        ?>
          <div class='col-sm-4' id='candidate-collumn'>
            <div class='panel panel-success'>
              <div class='panel-heading'>
               <h5 style='color: #fff;'><?php echo $category['name']; ?></h5>
              </div>
              <div class='panel-body'>
              <?php for ($y=0; $y < count($candidates); $y++): ?>
                <?php
                $candidate = $candidates[$y];

                // gets the values of all votes
                // use in the js below
                array_push($candidate_votes, $candidate['votes']);
                
                if ($votes[0]['all_votes'] == 0) {
                    $vote_percentage = 0;
                } else {
                    $vote_percentage = 
                       round($candidate['votes']/$votes[0]['all_votes']*100);
                }
                ?>
                <img src='candidates/<?php echo $candidate['photo']; ?>' 
                  width='150'>
               <h7> 
                  <strong>
                  <?php 
                  echo "$candidate[fname] $candidate[mname] $candidate[lname]"; 
                  ?>
                  </strong>                  
                  </h7>
				<h5>
				  <?php 
                if (empty($candidate['votes'])) {
                    $candidate['votes'] = 0;
                }
                if ($candidate['votes'] > 1) {
                    echo "($candidate[votes] votes) $vote_percentage%"; 
                } else {
                    echo "($candidate[votes] vote) $vote_percentage%"; 
                }
                ?>
				</h5>
                <div class='progress'>
                  <div class='progress-bar progress-bar-striped active' 
                    valuenow='<?php echo $vote_percentage; ?>'
                    valuemin='0' valuemax='100'
                    style='width:<?php echo "$vote_percentage%"; ?>;'>
                  </div>
                </div>
              <?php endfor; ?>
              </div>
            </div>
        </div>
        <?php endif; ?>
      <?php endfor; ?>
      <span id='candidate-votes' 
        data-val='<?php echo json_encode($candidate_votes); ?>'>
      </span>
      <?php 
      if (!$crud->read('candidates')) {
          echo "<div id='alert-info'>No results found.</div>";
      } 
      ?>
      </div>
    </div>
  </div>
  <script src='js/jquery-3.1.1.min.js'></script>
  <script src='js/bootstrap.min.js'></script>
  <script src='js/alert-bootstrap.js'></script>
  <script src='js/clear-collumn-float.js'></script>
  <script>
  // update election result if has changes
  setInterval(function(){    
      var candidate_votes = $('#candidate-votes').data('val');
      $.get(
          'check-election-result.php',
          function(data) {
              for (var x = 0; x < candidate_votes.length; x++) {
                  // if they are not equal it means there are changes
                  if (data[x] !== candidate_votes[x])  {
                      $.get(
                          'election-result.php',
                          function(data) {
                              $('#election-result').html(data);
                          }
                      );
                      break;
                  }
              }
          },
          'json'
      );
  // check if there is changes every 30 seconds
  }, 30000);

  $(function(){
      $('[name="reset_result"]').click(function(e) {
          var is_confirm = confirm('Are you sure you want to reset the result?');

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