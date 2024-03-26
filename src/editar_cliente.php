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
    if (empty($_POST['contrato']) || empty($_POST['nombre']) || empty($_POST['planta']) || empty($_POST['telefono']) || empty($_POST['direccion']) || empty($_POST['nom_folio'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son requeridos</div>';
    } else {
        $idcliente = $_POST['id'];
        $contrato = $_POST['contrato'];
        $nombre = $_POST['nombre'];
        $planta = $_POST['planta'];
        $telefono = $_POST['telefono'];
        $direccion = $_POST['direccion'];
        $nom_folio = $_POST['nom_folio'];
            $sql_update = mysqli_query($conexion, "UPDATE cliente SET contrato = '$contrato' , nombre = '$nombre' , planta = '$planta' , telefono = '$telefono', direccion = '$direccion', nom_folio = '$nom_folio' WHERE idcliente = $idcliente");

            if ($sql_update) {
                $alert = '<div class="alert alert-success" role="alert">Cliente Actualizado correctamente</div>';
            } else {    
                $alert = '<div class="alert alert-danger" role="alert">Error al Actualizar el Cliente</div>';
            }
    }
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
        $contrato = $data['contrato'];
        $nombre = $data['nombre'];
        $planta = $data['planta'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
        $nom_folio = $data['nom_folio'];
    }
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-6 m-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Modificar Cliente
                </div>
                <div class="card-body">
                    <form class="" action="" method="post">
                        <?php echo isset($alert) ? $alert : ''; ?>
                        <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
                        <div class="form-group">
                            <label for="contrato">Contrato</label>
                            <input type="text" placeholder="Ingrese Numero de Contrato" name="contrato" class="form-control" id="contrato" value="<?php echo $contrato; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Proyecto</label>
                            <input type="text" placeholder="Ingrese Proyecto" name="nombre" class="form-control" id="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="form-group">
                            <label for="planta">Planta</label>
                            <input type="text" placeholder="Ingrese Planta" name="planta" class="form-control" id="planta" value="<?php echo $planta; ?>">
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="number" placeholder="Ingrese Teléfono" name="telefono" class="form-control" id="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" placeholder="Ingrese Direccion" name="direccion" class="form-control" id="direccion" value="<?php echo $direccion; ?>">
                        </div>
                        <div class="form-group">
                            <label for="nom_folio">Nomenclatura de Folio</label>
                            <input type="text" placeholder="Ingrese 3 letras" name="nom_folio" class="form-control" id="nom_folio" value="<?php echo $nom_folio; ?>">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-edit"></i> Editar Cliente</button>
                        <a href="clientes.php" class="btn btn-danger">Atras</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- /.container-fluid -->
<?php include_once "includes/footer.php"; ?>