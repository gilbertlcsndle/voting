<?php 
if (isset($_POST['student'])) {
    $student = $_POST['student'];
}

$GRADELEVEL_CHOICES = array('Grade 7', 'Grade 8', 'Grade 9', 'Grade 10');
$SECTION_CHOICES = array(
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
<div class='form-group'>
  <label for='class'>Class:</label><br>
  <div class='col-sm-6 no-gutters'>
    <select class="form-control" name='student[grade_level]' required>
      <option value="" selected>--Grade level--</option>
      <?php foreach ($GRADELEVEL_CHOICES as &$v): ?>
        <option <?php if (!empty($student) and $student['grade_level'] == $v) {
                echo 'selected'; 
            }
            ?>
        >
            <?php echo $v ?>    
        </option>
      <?php endforeach ?>
      <?php unset($v); ?>
    </select>
  </div>
  <div class='col-sm-6 no-gutters'>
	<select class="form-control" name='student[section]' required>
		<option value="" selected>--Section--</option>
		<?php foreach ($SECTION_CHOICES as &$v): ?>
    		<option <?php if (!empty($student) and $student['section'] == $v) {
                    echo 'selected'; 
                }
                ?>
            >
                <?php echo $v ?>    
            </option>
        <?php endforeach ?>
        <?php unset($v); ?>
	</select>
  </div>
</div>