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
<body class="text-center">
    <img src="logo/sjnhs.png" alt="SJNHS logo" width="100" /><br />
    <h5>SJNHS VOTING SYSTEM</h5>
    San Juan National High School <br />
    Ili Sur, San Juan, La Union
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
        <div style="margin-top:40px;">
          <h6><?php echo $category['name']; ?></h6>
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
          <div>
            <img src='candidates/<?php echo $candidate['photo']; ?>' 
              width='90'>
          </div>
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
        </div>
        <?php endfor; ?>
      <?php endif; ?>
    <?php endfor; ?>
  <script>
    window.print();
  </script>
</body>
</html>