<?php
if (isset($_POST['update'])) {
    include 'functions/encrypt-decrypt.php';

    // sets new variable for the data in the form
    $profile = $_POST['profile'];
    $account = $_POST['account'];
    
    if (isset($_POST['student'])) {
        $student = $_POST['student'];
    }

    $is_valid=False;

    // validates and encrypt password if valid
    if (!empty($account['password']) and !empty($account['confirm_password'])) {
        if ($account['password'] !== $account['confirm_password']) {
            echo "<div id='alert-info'>Confirm password did not match.</div>";
        } else {
            unset($account['confirm_password']);
            $account['password'] = encrypt_pass($account['password']);

            $is_valid=True;
        }
    } else {
        // remove password in data to make password optional
        unset($account['password'], $account['confirm_password']);

        $is_valid=True; 
    }

    if ($is_valid) {
        // updates profile
        $update_profile = $crud->update(
            $_POST['profile'], 
            'profiles', 
            'id_no', 
            "'$_GET[id_no]'"
        );

        // updates student
        if (!empty($student)) {
            // updates when already existed else create new one
            $was_student = $crud->get(
                'students', 
                'id_no', 
                'id_no', 
                "'$_GET[id_no]'"
            );

            if ($was_student) {
                $update_student = $crud->update(
                    $student, 
                    'students', 
                    'id_no', 
                    "'$_GET[id_no]'"
                );
            } else {
                $student['id_no'] = $profile['id_no'];
                $update_student = $crud->create('students', $student);
            }
                
        } else {
            $update_student = true;
        }

        // updates account
        $update_account = $crud->update($account, 'accounts', 'id_no', "'$_GET[id_no]'");
        
        if ($update_profile and $update_student and $update_account) {
            // header("location:$_GET[last_page]");
        } else {
            echo "<div id='alert-danger'>Please check your internet connection
                and try again.</div>";
        }
    }
}

?>