<?php
include_once '../modelo/config.php';


// Consulta SQL para obtener las denuncias pendientes
$sqlDenuncias = "SELECT denuncias.*, cursos.*, 
                 (SELECT email FROM users WHERE users.user_id = denuncias.user_id_reporter) AS reporter_email,
                 (SELECT email FROM users WHERE users.user_id = denuncias.user_id_seller) AS seller_email
                 FROM denuncias 
                 JOIN cursos ON cursos.curso_id = denuncias.curso_id  
                 WHERE denuncias.aprobada = 1";

$resultDenuncias = $conn->query($sqlDenuncias);

// Consulta SQL para obtener los cursos "Correctos para cobrar"
$sqlCursosCorrectos = "SELECT pagos.*, informacion_pago.*, pagos.user_id AS user_id, 
                       (SELECT email FROM users WHERE users.user_id = pagos.user_id) AS email,
                       (SELECT email FROM users WHERE users.user_id = pagos.creator_user_id) AS creator_email
                       FROM pagos
                       JOIN informacion_pago ON pagos.creator_user_id = informacion_pago.user_id
                       WHERE pagos.status = 'approved';";

$resultCursosCorrectos = $conn->query($sqlCursosCorrectos);

?>
<style>/* styles.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

nav {
    background-color: #333;
    color: #fff;
    padding: 10px 20px;
    text-align: right;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #333;
    color: #fff;
}
</style>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
    <title>Administrador Edu-Connect</title>
</head>
<body>
    <h1>Panel de Administrador de Edu-Connect</h1>

    <h2 style="background-color:red;text-align:center;">Denuncias Pendientes de Revisión</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ID del Denunciante</th>
            <th>Email del Denunciante</th>
            <th>ID del Vendedor</th>
            <th>Email Vendedor</th>
            <th>ID del Curso</th>
            <th>Fecha Denuncia</th>
            <th>Motivo</th>
            <th>Contenido</th>

        </tr>
        <?php
        // Muestra las denuncias pendientes
        while ($row = $resultDenuncias->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["denuncia_id"] . "</td>";
            echo "<td>" . $row["user_id_reporter"] . "</td>";
            echo "<td>" . $row["reporter_email"] . "</td>";
            echo "<td>" . $row["user_id_seller"] . "</td>";
            echo "<td>" . $row["seller_email"] . "</td>";
            echo "<td>" . $row["curso_id"] . "</td>";
            echo "<td>" . $row["fecha_denuncia"] . "</td>";
            echo "<td>" . $row["motivo"] . "</td>";
            echo "<td><a href='../modelo/contenidocurso/" . $row["contenido"] . "' download>Descargar</a></td>";


            echo "</tr>";
        }
        ?>
    </table>

    <h2 style="background-color:green;text-align:center;">Transacciónes Correctas</h2>
    <table border="1">
        <tr>
            <th>ID del Pago</th>
            <th>Payment ID</th>
            <th>Estado</th>
            <th>Payment Tipo</th>
            <th>Orden Comerciante</th>
            <th>Fecha del registro</th>
            <th>Comprador ID</th>
            <th>Curso ID</th>
            <th>Vendedor ID</th>
            <th>Pago ID</th>
            <th>Cbu_Cvu</th>
            <th>Titular de Cuenta</th>
            <th>Email Comprador</th>
            <th>Email Vendedor</th>



            

            
        </tr>
        <?php
        // Muestra los cursos "Correctos para cobrar"
        while ($row = $resultCursosCorrectos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["payment_id"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "<td>" . $row["payment_type"] . "</td>";
            echo "<td>" . $row["merchant_order_id"] . "</td>";
            echo "<td>" . $row["fecha_registro"] . "</td>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["curso_id"] . "</td>";
            echo "<td>" . $row["creator_user_id"] . "</td>";
            echo "<td>" . $row["informacion_pago_id"] . "</td>";
            echo "<td>" . $row["cbu_cvu"] . "</td>";
            echo "<td>" . $row["titular_cuenta"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["creator_email"] . "</td>";


            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
