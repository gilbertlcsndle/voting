<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=voting', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $error) {
    echo 'Connection failed: '.$error->getMessage();
}

?>