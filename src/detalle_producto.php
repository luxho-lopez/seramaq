<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

// Validar producto

if (empty($_REQUEST['id'])) {
    header("Location: productos.php");
} else {
    $id_producto = $_REQUEST['id'];
    if (!is_numeric($id_producto)) {
        header("Location: productos.php");
    }
    $query_producto = mysqli_query($conexion, "SELECT p.*, u.* FROM producto p INNER JOIN unidad u ON p.u_medida = u.id_unidad WHERE codproducto = $id_producto");
    $result_producto = mysqli_num_rows($query_producto);

    if ($result_producto > 0) {
        $data_producto = mysqli_fetch_assoc($query_producto);
    } else {
        header("Location: productos.php");
    }
}
?>
<div class="row mt-5">
    <div class="col-lg-6">

        <div class="card">
            <div class="card-header bg-primary text-white">
                Detalles del material
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">Código</th>
                            <td><?php echo $data_producto['codigo']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Producto</th>
                            <td><?php echo $data_producto['descripcion']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Stock</th>
                            <td><?php echo $data_producto['existencia']; ?>
                                <?php echo $data_producto['unidad_medida']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Tipo</th>
                            <?php if ($data_producto['tipo'] == 1) {
                                $tipo = '<span class="badge badge-pill badge-success"></span>';
                            } else if ($data_producto['tipo'] == 2) {
                                $tipo = '<span class="badge badge-pill badge-success"><i class="fas fa-solid fa-recycle"></i></span>';
                            } else if ($data_producto['tipo'] == 3) {
                                $tipo = '<span class="badge badge-pill badge-danger"><i class="fas fa-solid fa-arrow-right"></i></span>';
                            } ?>
                            <td><?php echo $tipo; ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="mt-5" style="display: flex; justify-content: right;">
            <a href="productos.php" class="btn btn-danger">Atras</a>
        </div>

    </div>
    <div class="col-md-6">
        <div style="width: 100%; display: flex; justify-content: center;">
            <img style="width: 70%;" src="../assets/img/items/<?php echo $data_producto['name_archivo']; ?>" alt="" srcset="">
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>