<?php
include "conexion.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_eliminar = "DELETE FROM soportes WHERE id = $id";
    if ($conexion->query($sql_eliminar) === TRUE) {
        header("Location: lista_soporte.php");
    } else {
        echo "Error al eliminar: " . $conexion->error . "<br><br>";
    }
} else {
    echo "No se seleccionó ningún soporte para eliminar.<br><br>";
}
?>