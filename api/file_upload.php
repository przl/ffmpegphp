<?php

if (move_uploaded_file($_FILES["file"]["tmp_name"], "../queue/" . $_FILES["file"]["name"])) {
    $resolution = $_GET['resolution'];
    $email      = $_GET['email'];
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo 'Please upload a file...';
    return;
}
