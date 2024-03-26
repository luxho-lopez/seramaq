<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: clientes.php");
}
$idcliente = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $idcliente = $data['idcliente'];
        $nombre = $data['nombre'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Proyecto
                </div>
                <div class="card-body">

                    <?php echo isset($alert) ? $alert : ''; ?>
                    <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                    <div class="form-group">
                        <label for="nombre"><?php echo $nombre; ?></label>
                        <!-- <input type="text" placeholder="Ingrese Nombre" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>"> -->
                    </div>
                    <div class="form-group">
                        <label for="telefono"><?php echo $telefono; ?></label>
                        <!-- <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>"> -->
                    </div>
                    <div class="form-group">
                        <label for="direccion"><?php echo $direccion; ?></label>
                        <!-- <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>"> -->
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php

    // $query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente");
    $query = mysqli_query($conexion, "SELECT dv.*, p.codigo, p.descripcion FROM detalle_venta dv INNER JOIN producto p ON dv.id_producto = p.codproducto WHERE id_cliente = '$idcliente'");
    ?>
    <br><br>
    <table class="table table-light" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Material</th>
                <th>Total</th>
                <th>Fecha</th>
                <!-- <th></th> -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['codigo']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['cantidad']; ?></td>
                    <td><?php echo $row['at_date']; ?></td>
                    <!-- <td>
                        <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                    </td> -->
                </tr>
            <?php } ?>
        </tbody>
    </table>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>