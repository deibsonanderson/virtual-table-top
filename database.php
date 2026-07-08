<?php
$connection = null;
try {
    $connection = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($connection, 'utf8');
    mysqli_select_db($connection, "rpg_online");
} catch (Exception $e) {
    var_dump($e);
    echo '<pre>';
    die();
}
?>
