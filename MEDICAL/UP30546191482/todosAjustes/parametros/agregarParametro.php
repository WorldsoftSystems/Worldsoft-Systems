<?php
require_once "../../conexion.php";

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = isset($_POST['id']) ? $_POST['id'] : null;
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

    // Preparar la consulta SQL para insertar los datos
    $query = "INSERT INTO parametro_sistema 
              (id, inst, razon_social, c_interno, c_pami, cuit, u_efect, clave_efect, mail, puerta, dir, localidad, cod_sucursal, tel, num_hist_amb, num_hist_int) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la sentencia
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("issssssssissisii", $id, $inst, $razon_social, $c_interno, $c_pami, $cuit, $u_efect, $clave_efect, $mail, $puerta, $dir, $localidad, $cod_sucursal, $tel, $num_hist_amb, $num_hist_int);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            echo "Datos agregados correctamente.";
            header('Location: ./parametros.php');
        } else {
            echo "Error al agregar los datos: " . $stmt->error;
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Solicitud inválida.";
}
?>
