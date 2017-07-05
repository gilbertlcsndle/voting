<?php
$voter = $crud->get('accounts', 'is_voted', 'id_no', "'$_SESSION[id_no]'");

if (!$voter['is_voted']) { // proceed if the account didn't vote yet
    $is_valid=false;

    if (!isset($error)) {
        $is_valid=true;
    }

    if ($is_valid) {    
        $is_success = true;

        foreach ($categories as &$category) {
            if (!empty($_POST['vote'][$category['name']])) {
                foreach ($_POST['vote'][$category['name']] as $candidate) {
                    $query = $conn->prepare(
                        "UPDATE candidates SET votes=votes+1 WHERE pk=:pk"
                    );

                    if ($query->execute($candidate)) {
                        $is_success = true;
                    } else {
                        $is_success = false;
                        break;
                    }
                }
            }
        }
        unset($category, $candidate);

        if ($is_success) {
            $query2 = $conn->prepare(
                 "UPDATE accounts SET is_voted=1 WHERE id_no=:id_no"
            );
            if ($query2->execute(
                    array('id_no'=>$_SESSION['id_no'])
                )
            ) {
                echo "<div id='alert-success'>
                    Your vote has been submitted.</div>";
            } else {
                echo "<div id='alert-danger'>
                    Please check your internet connection and try again.</div>";
            }  
        } else {
            echo "<div id='alert-danger'>
                Please check your internet connection and try again.</div>";
        } 
    }
} else {
    echo "<div id='alert-warning'>This account was already voted.
        Please report to the administrator if this is a bug.</div>";
}
?>