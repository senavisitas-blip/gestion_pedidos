<?php
require "conexion.php";
$mensaje = "";

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre    = trim($_POST["nombre"]);
    $documento = trim($_POST["documento"]);
    $telefono  = trim($_POST["telefono"]);
    $correo    = trim($_POST["correo"]);
    $direccion = trim($_POST["direccion"]);

    if ($nombre === "" || $documento === "") {
        $mensaje = "⚠️ Nombre y documento son obligatorios.";
    } else {
        // Consulta preparada (evita inyección SQL)
        $sql = $conexion->prepare(
            "INSERT INTO clientes (nombre, documento, telefono, correo, direccion)
             VALUES (?, ?, ?, ?, ?)"
        );
        $sql->bind_param("sssss", $nombre, $documento, $telefono, $correo, $direccion);

        if ($sql->execute()) {
            $mensaje = "✅ Cliente guardado correctamente.";
        } else {
            $mensaje = "❌ Error al guardar: " . $conexion->error;
        }
    }
}

// Listar clientes
$clientes = $conexion->query("SELECT * FROM clientes ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de clientes</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 30px auto; padding: 0 15px; }
        form { display: grid; gap: 10px; }
        input, button { padding: 10px; font-size: 16px; }
        button { background: #39a900; color: white; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Registro de clientes</h2>

    <?php if ($mensaje): ?>
        <p><strong><?= $mensaje ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo *" required>
        <input type="text" name="documento" placeholder="Documento *" required>
        <input type="tel" name="telefono" placeholder="Teléfono">
        <input type="email" name="correo" placeholder="Correo electrónico">
        <input type="text" name="direccion" placeholder="Dirección">
        <button type="submit">Guardar cliente</button>
    </form>

    <h3>Clientes registrados</h3>
    <table>
        <tr><th>ID</th><th>Nombre</th><th>Documento</th><th>Teléfono</th><th>Correo</th></tr>
        <?php while ($c = $clientes->fetch_assoc()): ?>
            <tr>
                <td><?= $c["id"] ?></td>
                <td><?= htmlspecialchars($c["nombre"]) ?></td>
                <td><?= htmlspecialchars($c["documento"]) ?></td>
                <td><?= htmlspecialchars($c["telefono"]) ?></td>
                <td><?= htmlspecialchars($c["correo"]) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>