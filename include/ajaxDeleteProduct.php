<?php
if ($_POST['id']) {

    include $_SERVER['DOCUMENT_ROOT'] . '/include/functions.php';

    $id = (int) $_POST['id'];
     if (deleteProduct($id)){
        echo 'true';
     } else {
        echo 'false';
     }
}