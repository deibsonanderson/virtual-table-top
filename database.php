<?php
$conexao = null;
try {
    $conexao = mysqli_connect("localhost", "root", "");
    mysqli_set_charset($conexao, 'utf8');
    mysqli_select_db($conexao, "rpg_online");
} catch (Exception $e) {
    var_dump($e);
    echo '<pre>';
    die();
}
?>
