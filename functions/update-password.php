<?php
if (isset($_POST['account'])) {
    require 'crud.php';
    require 'encrypt-decrypt.php';

    if (
        $_POST['account']['new_password'] == 
        $_POST['account']['confirm_password']
    ) {
        $is_valid=true;   
    } else {
        $is_valid=false;
        echo "<div id='alert-info'>Confirm password did not match.</div>";
    }

    $crud = new crud();

    if ($is_valid) {
        $get_pass = $crud->get(
            'accounts', 
            'password', 
            'id_no',
            "'$_SESSION[id_no]'"   
        );

        // true if the password is matched
        $is_valid = decrypt_pass(
            $_POST['account']['old_password'], 
            $get_pass['password']
        );

        if (!$is_valid) {
            echo "<div id='alert-danger'>Your old password is incorrect.</div>";
        }
    }

    if ($is_valid) {
        $new_password  = encrypt_pass($_POST['account']['new_password']);
        $password_data = array('password'=> $new_password);

        $is_updated = $crud->update(
            $password_data, 
            'accounts',
            'id_no', 
            "'$_SESSION[id_no]'"
        );

        if ($is_updated) {
            echo "<div id='alert-success'>
              Your password has been successfully changed!
            </div>";
        } else {
            echo "<div id='alert-danger'>
              Please check your internet connection and try again.
            </div>";
        }
    }
}
?>