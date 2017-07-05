<?php 
function encrypt_pass($input_pass) {
    $chars = substr(sha1(mt_rand()), 0, 22);
    $salt  = sprintf('$2x$10$%s', $chars);

    return crypt($input_pass, $salt);
}

function decrypt_pass($input_pass, $password) {
    return crypt($input_pass, $password) == $password ? true : false;
}
?>