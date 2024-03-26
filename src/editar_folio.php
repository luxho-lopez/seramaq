<?php include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['folio_venta'])) {
        $alert = '<div class="alert alert-danger" role="alert">El folio es requerido</div>';
    } else {
        $id = $_POST['id'];
        $folio_venta = $_POST['folio_venta'];
            $sql_update = mysqli_query($conexion, "UPDATE ventas SET folio_venta = '$folio_venta' WHERE id = $id");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Folio Actualizado correctamente</div>';
            } else {    
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Folio</div>';
            }
    }
}
// Mostrar Datos

if (empty($_REQUEST['id'])) {
    header("Location: lista_ventas.php");
}
$id = $_REQUEST['id'];
$sql = mysqli_query($conexion, "SELECT * FROM ventas WHERE id = $id");
$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header("Location: clientes.php");
} else {
    if ($data = mysqli_fetch_array($sql)) {
        $id = $data['id'];
        $folio_venta = $data['folio_venta'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Folio
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="form-group">
                            <label for="folio_venta">Folio</label>
                            <input type="text" placeholder="Ingrese Numero de folio" name="folio_venta" class="form-control" id="folio_venta" value="<?php echo $folio_venta; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Folio</button>
                        <a href="lista_ventas.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>