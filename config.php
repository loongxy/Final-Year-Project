<?php
$conn = mysqli_connect('localhost', 'root', '', 'beauty');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

date_default_timezone_set('Asia/Kuala_Lumpur');
?>