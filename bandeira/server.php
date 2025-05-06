<?php
try {
    include('banco.php');
    
    $identificador = $_POST["identificador"];

    if ($identificador != null && $_POST["tipo_operacao"] == "1") {
        $sql = " UPDATE ficha_bandeira SET ";
        foreach ($_POST as $chave => $valor) {
            if ($valor != null && $chave != 'tipo_operacao' && $chave != 'identificador') {
                $sql .= $chave . " = '" . $valor . "', ";
            }
        }
        $sql = substr($sql, 0, - 2);
        $sql .= " WHERE identificador = '" . $identificador . "'";

        $isUpdated = mysqli_query($conexao, $sql);
        // var_dump($sql);
    }

    mysqli_close($conexao);
} catch (Exception $e) {
    var_dump($e);
    echo '<pre>';
    die();
}
?>
