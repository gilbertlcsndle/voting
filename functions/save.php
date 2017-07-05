<?php
if (isset($_POST['save'])) {
    include 'functions/encrypt-decrypt.php';
    // sets new variable for the data in the form
    $profile = $_POST['profile'];
    $account = $_POST['account'];
    
    if (isset($_POST['student'])) {
        $student = $_POST['student'];
    }

    // validations
    $is_valid=false;
    if (
        !empty($account['password']) and
        !empty($account['confirm_password'])
    ) {
        if ($account['password'] == 
            $account['confirm_password']) {
            $is_valid=true;
        } else {
            echo "<div id='alert-info'>Confirm password did not match.</div>";
            $is_valid=false;
        }
    } else {
        echo "<div id='alert-info'>Password is required.</div>";
    }

    if ($is_valid) {
        $id_no = $_POST['profile']['id_no'];
        if (
            $crud->read(
                'profiles', 
                'id_no', 
                'id_no', 
                "'$id_no'"
            )
        ) {
            echo "<div id='alert-info'>Your id was already registered.</div>";
            $is_valid=false;
        } else {
            $is_valid=true;
        }
    }

    // end

    // save data

    if ($is_valid) {    
        $save_profile = $crud->create('profiles', $profile);

        if (!empty($student)) {
            $student['id_no'] = $profile['id_no'];
            $save_student     = $crud->create('students', $student);
        } else {
            $save_student=true;
        }

        $account['id_no'] = $profile['id_no'];
        
        if (empty($_SESSION)) {
            $account['user_level'] = 'Student';
        }

        $account['is_active']=false;
    
        if ($account['user_level'] == 'Administrator') {
            $account['is_active']=true;
        }

        unset($account['confirm_password']);
        
        // encrypts the password using the function encrypt_pass
        $account['password'] = encrypt_pass($account['password']);

        $save_account = $crud->create('accounts', $account);

        if ($save_profile and $save_student and $save_account) {
            // resets the form. means not populated form
            $profile = $account = $student = array();

            if (isset($_GET['last_page'])) {
                echo "<div id='alert-success'>You've successfully registered.</div>";
            } else {
                echo "<div id='alert-success'>You've successfully registered.</div>";
                echo "
                    <script>
                        setTimeout(function(){
                            window.location = 'login.php';
                        }, 2000);
                    </script>
                ";
            }
        } else {
            echo "<div id='alert-danger'>
                Please check your internet connection and try again.</div>";
        }
    }
}            
?>