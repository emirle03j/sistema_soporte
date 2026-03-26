<?php
include "conexion.php";

if(!isset($_GET['id'])) {
    die("No se seleccionó ningún técnico para editar.");    
}
$id = $_GET['id'];
$nombre = $_GET['nombre'];
$apellido = $_GET['apellido'];
$cedula = $_GET['cedula'];
$cargo = $_GET['cargo'];

try {
    $sql = "UPDATE tecnicos SET nombre = ?, apellido = ?, cedula = ?, cargo = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $apellido, $cedula, $cargo, $id]);
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    echo "Error al editar: " . $e->getMessage() . "<br><br>";
}
?>
