<?php
include "conexion.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_eliminar = "DELETE FROM departamentos WHERE id = $id";
    if ($conexion->query($sql_eliminar) === TRUE) {
        $returnUrl = isset($_GET['returnUrl']) ? $_GET['returnUrl'] : 'lista_departamento.php';
        header("Location: " . $returnUrl);
    } else {
        echo "Error al eliminar: " . $conexion->error . "<br><br>";
    }
} else {
    echo "No se seleccionó ningún departamento para eliminar.<br><br>";
}
?>