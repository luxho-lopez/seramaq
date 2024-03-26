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
if (!empty($_POST)) {
	$alert = "";
	if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['precio']) || empty($_POST['u_medida']) || empty($_POST['tipo'])) {
		$alert = '<div class="alert alert-primary" role="alert">
              Todo los campos son requeridos
            </div>';
	} else {
		$codproducto = $_GET['id'];
		$codigo = $_POST['codigo'];
		$producto = $_POST['producto'];
		$precio = $_POST['precio'];
		$u_medida = $_POST['u_medida'];
		$tipo = $_POST['tipo'];

		// $nom_archivo = $_FILES['archivo']['name'];
		// $temp_archivo = $_FILES['archivo']['tmp_name'];
		// $ruta = "../assets/img/items/" . $nom_archivo;

		// move_uploaded_file($temp_archivo, $ruta);

		// $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio= '$precio', u_medida= '$u_medida', tipo= '$tipo', name_archivo= '$nom_archivo' WHERE codproducto = $codproducto");
		$query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio= '$precio', u_medida= '$u_medida', tipo= '$tipo' WHERE codproducto = $codproducto");
		if ($query_update) {
			$alert = '<div class="alert alert-primary" role="alert">
              Producto Modificado
            </div>';
		} else {
			$alert = '<div class="alert alert-primary" role="alert">
                Error al Modificar
              </div>';
		}
	}
}

// Validar producto

if (empty($_REQUEST['id'])) {
	header("Location: productos.php");
} else {
	$id_producto = $_REQUEST['id'];
	if (!is_numeric($id_producto)) {
		header("Location: productos.php");
	}
	$query_producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
	$result_producto = mysqli_num_rows($query_producto);

	if ($result_producto > 0) {
		$data_producto = mysqli_fetch_assoc($query_producto);
	} else {
		header("Location: productos.php");
	}
}
?>
<div class="row">
	<div class="col-lg-6 m-auto">

		<div class="card">
			<div class="card-header bg-primary text-white">
				Modificar producto
			</div>
			<div class="card-body">
				<form action="" method="post" enctype="multipart/form-data">
					<?php echo isset($alert) ? $alert : ''; ?>
					<div class="form-group">
						<label for="codigo">Código del producto</label>
						<input type="text" placeholder="Ingrese código del producto" name="codigo" id="codigo" class="form-control" value="<?php echo $data_producto['codigo']; ?>">
					</div>
					<div class="form-group">
						<label for="producto">Producto</label>
						<input type="text" class="form-control" placeholder="Ingrese nombre del producto" name="producto" id="producto" value="<?php echo $data_producto['descripcion']; ?>">

					</div>
					<div class="form-group">
						<label for="precio">Precio</label>
						<input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio" value="<?php echo $data_producto['precio']; ?>">

					</div>

					<?php
						$sqlUnidad = "SELECT * FROM unidad ORDER BY unidad_medida";
						$unidades = $conexion->query( $sqlUnidad );
					?>

					<div class="form-group">
						<label for="u_medida">Unidad de medida</label>
						<select name="u_medida" id="u_medida" class="form-control" aria-label="Seleccione una unidad de medida">
							<!-- <option value="1">OTRO</option> -->
							<?php while ($unidad = $unidades->fetch_assoc()) { ?>
								<option <?php echo $unidad['id_unidad']===$data_producto['u_medida'] ? "selected='selected'":"" ?> value="<?php echo $unidad["id_unidad"] ?>"><?= $unidad["unidad_medida"] ?></option>
							<?php } ?>
						</select>
					</div>

					<?php $unidades->data_seek(0); ?>

					<div class="form-group">
						<label for="tipo">Tipo de material</label>
						<select name="tipo" id="tipo" class="form-control" aria-label="Seleccione el tipo de material">
							<option <?php echo $data_producto['tipo']==='1' ? "selected='selected'":"" ?> value="1">OTRO</option>
							<option <?php echo $data_producto['tipo']==='2' ? "selected='selected'":"" ?> value="2">USO</option>
							<option <?php echo $data_producto['tipo']==='3' ? "selected='selected'":"" ?> value="3">CONSUMO</option>
						</select>
					</div>
					<!-- <div class="form-group">
                        <label for="archivo" class="form-label">Imagen del material</label>
						<div style="width: 100%; display: flex; justify-content: center;">
							<img style="width: 50%;" src="../assets/img/items/<?php echo $data_producto['name_archivo']; ?>" alt="" srcset="">
						</div>
                    </div> -->
					<input type="submit" value="Actualizar Producto" class="btn btn-primary">
					<a href="productos.php" class="btn btn-danger">Atras</a>
				</form>
			</div>
		</div>
	</div>
</div>
<?php include_once "includes/footer.php"; ?>