<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $u_medida = $_POST['u_medida'];
    $tipo = $_POST['tipo'];

    $nom_archivo = $_FILES['archivo']['name'];
    $temp_archivo = $_FILES['archivo']['tmp_name'];
    $ruta = "../assets/img/items/" . $nom_archivo;

    move_uploaded_file($temp_archivo, $ruta);

    $usuario_id = $_SESSION['idUser'];
    $alert = "";
    if (empty($codigo) || empty($producto) || empty($precio) || $precio <  0 || empty($cantidad) || $cantidad < 0) {
        $alert = '<div class="alert alert-danger" role="alert">
            Todo los campos son obligatorios
            </div>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
                    El código ya existe
                </div>';
        } else {
            $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,existencia,u_medida,tipo,name_archivo,usuario_id) values ('$codigo', '$producto', '$precio', '$cantidad', '$u_medida', '$tipo', '$nom_archivo', '$usuario_id')");
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
            Producto Registrado
            </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
            Error al registrar el producto
            </div>';
            }
        }
    }
}
?>
<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>
<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <!-- <th></th> -->
                <th>Código</th>
                <th>Producto</th>
                <!-- <th>Precio</th> -->
                <th>Stock</th>
                <th>Unidad</th>
                <th>Tipo</th>
                <!-- <th>Estado</th> -->
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../conexion.php";

            $query = mysqli_query($conexion, "SELECT p.*, u.*, c.*, ROW_NUMBER() OVER() AS 'row_number' FROM producto p INNER JOIN unidad u ON p.u_medida = u.id_unidad INNER JOIN clasificacion c ON p.tipo = c.id_clasificacion");
            $result = mysqli_num_rows($query);
            if ($result > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    if ($data['estado'] == 1) {
                        $estado = '<span class="badge badge-pill badge-success"><i class="fas fa-solid fa-check"></i></span>';
                    } else {
                        $estado = '<span class="badge badge-pill badge-danger"> X </span>';
                    }
            ?>
                    <tr>
                        <td><?php echo $data['row_number']; ?></td>
                        <!-- <td style=" display: flex; justify-content: center;">
                            <img style="margin: 0; width: 50px;" src="../assets/img/items/<?php echo $data['name_archivo']; ?>" alt="Imagen">
                        </td> -->
                        <td><?php echo $data['codigo']; ?></td>
                        <td><?php echo $data['descripcion']; ?></td>
                        <!-- <td><?php echo $data['precio']; ?></td> -->
                        <td><?php echo $data['existencia']; ?></td>
                        <td><?php echo $data['unidad_medida']; ?></td>
                        <!-- <td><?php echo $data['nombre_clasificacion']; ?></td> -->

                        <?php if ($data['tipo'] == 1) {
                            $tipo = '<span class="badge badge-pill badge-success"></span>';
                        } else if ($data['tipo'] == 2) {
                            $tipo = '<span class="badge badge-pill badge-success"><i class="fas fa-solid fa-recycle"></i></span>';
                        } else if ($data['tipo'] == 3) {
                            $tipo = '<span class="badge badge-pill badge-danger"><i class="fas fa-solid fa-arrow-right"></i></span>';
                        } ?>
                        <td><?php echo $tipo; ?></td>

                        <!-- <td><?php echo $estado ?></td> -->
                        <td>
                            <?php if ($data['estado'] == 1) { ?>
                                <a href="detalle_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-warning btn-sm"><i class='fas fa-regular fa-eye'></i></a>

                                <a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary btn-sm"><i class='fas fa-audio-description'></i></a>

                                <a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success btn-sm"><i class='fas fa-edit'></i></a>

                                <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                    <button class="btn btn-danger btn-sm" type="submit"><i class='fas fa-trash-alt'></i> </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>

    </table>
</div>
<div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Producto</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="codigo">Código de producto</label>
                        <input type="text" placeholder="Ingrese código de producto" name="codigo" id="codigo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                    </div>
                    <div class="form-group">
                        <label for="u_medida">Unidad de medida</label>
                        <select name="u_medida" id="u_medida" class="form-control" aria-label="Default select example">
                            <option value="1">PZ</option>
                            <option value="2">CAJA</option>
                            <option value="3">KG</option>
                            <option value="4">KIT</option>
                            <option value="5">LT</option>
                            <option value="6">M</option>
                            <option value="7">PAR</option>
                            <option value="8">PQ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo de material</label>
                        <select name="tipo" id="tipo" class="form-control" aria-label="Default select example">
                            <option value="1">OTRO</option>
                            <option value="2">USO</option>
                            <option value="3">CONSUMO</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="archivo" class="form-label">Imagen del material</label>
                        <input class="form-control" type="file" id="archivo" name="archivo">
                    </div>
                    <input type="submit" value="Guardar Producto" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>