<?php
/* creates a default admin user */
require_once 'crud.php';
require_once 'encrypt-decrypt.php';
function create_admin($id_no='', $password='') {

    $crud = new crud();

    $is_existed = $crud->get('accounts', 'id_no', 'id_no', "'$id_no'");

    if (!$is_existed) {
        $crud->create('profiles', array('id_no'=> $id_no));

        $password = encrypt_pass($password);
        
        $account_data = array(
            'id_no'=> $id_no, 
            'password'=> $password,
            'user_level'=> 'Administrator',
            'is_active'=> 1
        );
        
        $crud->create('accounts', $account_data);
    }
}
// change your desired value of the following
create_admin('admin', 'admin');

// this file goes in login.php
?>