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
         <h6 style='color: #fff;'><?php echo $category['name']; ?></h6>
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
            width='90'> 
            <strong>
            <?php 
            echo "$candidate[fname] $candidate[mname] $candidate[lname]"; 
            ?>
            </strong>                  
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