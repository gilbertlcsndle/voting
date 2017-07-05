<!DOCTYPE html>
<html >
<head>
<title>Automated Voting System for San Juan National High School</title>
<style type="text/css">
<!--
.style4 {font-size: 24px}
-->
</style>
</head>
<body>

<p>
  <?php session_start(); ?>
</p>
<table width="826" border="0.1">
  <tr>
    <td width="40">&nbsp;</td>
    <td width="732"><div align="center"><strong><img width="105" height="105" src="print_clip_image002.jpg" align="center" hspace="12" alt="Description: C:\Users\pc\Desktop\logo\sjnhs.png"></strong></div></td>
    <td width="40">&nbsp;</td>
  </tr>
  <tr>
    <td height="116">&nbsp;</td>
    <td><div align="center">
      <p align="center"><strong>AUTOMATED VOTING  SYSTEM FOR SAN JUAN NATIONAL HIGH SCHOOL</strong><br>
        San Juan National High School<br>
        Ili Sur, San Juan, La Union</p>
      <p>&nbsp;</p>
    </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>
  <?php  if (!empty($_SESSION) && $_SESSION['user_level'] == 'Administrator'): ?>
  
  <?php
      include 'functions/crud.php';

      $crud = new crud();
      
      $categories = $crud->read('categories');

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
</p>
<div class='col-sm-4' id='candidate-collumn'>
            <div class='panel panel-primary'>
              <div class='panel-heading'>
                <?php echo $category['name']; ?>
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
                <b>
                  <span class="style4">
                  <?php 
                  echo "$candidate[fname] $candidate[mname] $candidate[lname]"; 
                  ?>
                </span></b><span class="style4">./
                <?php 
                if (empty($candidate['votes'])) {
                    $candidate['votes'] = 0;
                }
                if ($candidate['votes'] > 1) {
                    echo "($candidate[votes] votes)"; 
                } else {
                    echo "($candidate[votes] vote)"; 
                }
                ?>
                </span>
                  <div class='progress'>
                  <div class='progress-bar progress-bar-striped active' 
                    valuenow='<?php echo $vote_percentage; ?>'
                    valuemin='0' valuemax='100'
                    style='width:<?php echo "$vote_percentage%"; ?>;'>                  </div>
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
  </script>
  <script> 

  window.print();
  </script>

<?php else: ?>

<?php endif; ?>
</body>
</html>