<?php
include_once "includes/header.php";
require_once "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "ventas";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
$query = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre, c.nom_folio, ROW_NUMBER() OVER() AS 'row_number' FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente ORDER BY v.id DESC");
?>
<table class="table table-light" id="tbl">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Folio</th>
            <th>Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?php echo $row['row_number']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['nom_folio']; ?> - <?php echo $row['folio_venta']; ?></td>
                <td><?php echo $row['fecha']; ?></td>
                <td>
                    <a href="editar_folio.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>
                    <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php include_once "includes/footer.php"; ?>