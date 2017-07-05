<?php
function check_url_active($current_url) {
    if (basename($_SERVER['PHP_SELF']) == $current_url) {
        echo "active";
    }
}

$nav_title_1 = 'Result';
$nav_title_2 = 'Admins';
$nav_title_3 = 'Students';
$nav_title_4 = 'Nominees';
$nav_title_5 = 'Categories';



$admin_links = array(
    'manage-admins.php'     => $nav_title_2,
    'manage-students.php'     => $nav_title_3,
    'manage-candidates.php' => $nav_title_4,
    'manage-categories.php' => $nav_title_5
);
?>
<nav class='navbar navbar-default bg-success'>

  <div class='container'>
    <div class='navbar-header'>
      <button type='button' class='navbar-toggle collapsed'
        data-toggle='collapse' data-target='#navbar'>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
      </button>
	  <img src="logo/sjnhs.png" class='navbar-brand' alt="SJNHS" class="img-responsive left-block">
      <a class='navbar-brand' href=''>
        SJNHS
      </a>
    </div>
    
    <div class='collapse navbar-collapse' id='navbar'>
      <ul class='nav navbar-nav'>
      <?php if ($_SESSION['user_level'] == 'Administrator'): ?>
        <li class='<?php check_url_active('index.php'); ?>'>
          <a href='index.php'><?php echo $nav_title_1; ?></a>
        </li>
        <?php foreach ($admin_links as $url => $title): ?>
          <li class='<?php check_url_active($url); ?>'>
            <a href='<?php echo $url; ?>'><?php echo $title ?></a>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if ($_SESSION['user_level'] == 'Student'): ?>
        <li class='<?php check_url_active('vote.php'); ?>'>
          <a href='vote.php'>Vote</a>
        </li>
      <?php endif; ?>
      </ul>
      <ul class='nav navbar-nav navbar-right'>
        <?php if ($_SESSION['user_level'] == 'Administrator'): ?>
          <li class='dropdown <?php check_url_active('change-password.php'); ?>'>
            <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
              <span class='glyphicon glyphicon-user'></span>
              <?php echo "$_SESSION[fname] $_SESSION[lname]"; ?>
              <span class='caret'></span>
            </a>
            <ul class='dropdown-menu'>
              <li class='<?php check_url_active('change-password.php'); ?>'>
                <a href='change-password.php'>Change password</a>
              </li>
              <li><a href='functions/logout.php' id='logout'>Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li><a href='functions/logout.php' id='logout'>Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
    
  </div>
</nav>