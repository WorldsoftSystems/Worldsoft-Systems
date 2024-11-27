<?php
require_once "../../conexion.php"; // Asegúrate de que la ruta a tu archivo de conexión es correcta

// Obtener los datos del formulario
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;value: 
    $inst = $_POST['inst'];
    $razon_social = $_POST['razon_social'];
    $c_interno = $_POST['c_interno'];
    $c_pami = $_POST['c_pami'];
    $cuit = $_POST['cuit'];
    $u_efect = $_POST['u_efect'];
    $clave_efect = $_POST['clave_efect'];
    $mail = $_POST['mail'];
    $puerta = $_POST['puerta'];
    $dir = $_POST['dir'];
    $localidad = $_POST['localidad'];
    $cod_sucursal = $_POST['cod_sucursal'];
    $tel = $_POST['tel'];
    $num_hist_amb = $_POST['num_hist_amb'];
    $num_hist_int = $_POST['num_hist_int'];

// Actualizar el registro en la base de datos usando mysqli
$query = "UPDATE parametro_sistema SET inst = ?, razon_social = ?, c_interno = ?, c_pami = ?, cuit = ?, u_efect = ?, clave_efect = ?, mail = ?, puerta = ?, dir = ?, localidad = ?, cod_sucursal = ?, tel = ?, num_hist_amb = ?, num_hist_int = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param(
    'ssssssssissisiii',
    $inst,
    $razon_social,
    $c_interno,
    $c_pami,
    $cuit,
    $u_efect,
    $clave_efect,
    $mail,
    $puerta,
    $dir,
    $localidad,
    $cod_sucursal,
    $tel,
    $num_hist_amb,
    $num_hist_int,
    $id
);
$stmt->execute();

// Verificar si la actualización fue exitosa
if ($stmt->affected_rows > 0) {
    echo "Registro actualizado correctamente.";
    header('Location: ./parametros.php');
} else {
    echo "No se realizaron cambios.";
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
