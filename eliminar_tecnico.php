<?php
include "conexion.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_eliminar = "DELETE FROM tecnicos WHERE id = $id";
    if ($conexion->query($sql_eliminar) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error al eliminar: " . $conexion->error . "<br><br>";
    }
} else {
    echo "No se seleccionó ningún técnico para eliminar.<br><br>";
}
?>