<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';


$id = $_GET['v'];
$idcliente = $_GET['cl'];
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);
$ventas = mysqli_query($conexion, "SELECT d.*, p.*, um.*, v.* FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto INNER JOIN unidad um ON p.u_medida = um.id_unidad INNER JOIN ventas v ON d.id_venta = v.id WHERE d.id_venta = $id");
$datos_venta = mysqli_query($conexion, "SELECT v.*, u.* FROM ventas v INNER JOIN usuario u ON v.id_usuario = u.idusuario WHERE id = $id");
$datos_venta = mysqli_fetch_array($datos_venta);

class PDF extends FPDF
{

    protected $col = 0; // Columna actual
    protected $y0;      // Ordenada de comienzo de la columna

    // Cabecera de página
    function Header()
    {
        global $datos, $datosC, $ventas, $datos_venta;

        // Logo
        $this->Image('../../assets/img/logo2.png', 10, 8, 50);
        // Arial bold 16
        $this->SetFont('Arial', 'B', 16);
        // Movernos a la derecha
        $this->Cell(160);
        // Título
        $this->SetTextColor(0, 176, 80);
        $this->Cell(30, 10, 'ORDEN DE SALIDA DE MATERIAL Y EQUIPO', 0, 0, 'R');
        // Salto de línea
        $this->Ln(10);

        // Arial  8
        $this->SetFont('Arial', '', 8);
        // Movernos a la derecha
        $this->Cell(130);
        // Fecha
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 8, 'FECHA: ', 0, 0, 'R');
        // Arial bold 8
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 8, $datos_venta['fecha'], 1, 0, 'C');
        // Salto de línea
        $this->Ln(8);

        // Arial bold 16
        $this->SetFont('Arial', '', 10);
        // Movernos a la derecha
        $this->Cell(1);
        // Direccion
        $this->Cell(30, 10, utf8_decode($datos['direccion']), 0, 0, 'L');

        // Folio
        // Movernos a la derecha
        $this->Cell(99);
        // Arial 8
        $this->SetFont('Arial', '', 8);
        $this->Cell(30, 8, 'FOLIO DE DOCUMENTO: ', 0, 0, 'R');
        // Arial bold 8
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(30, 8, '', 1, 0, 'C');
        // Salto de línea
        $this->Ln(5);

        // Telefono
        // Movernos a la derecha
        $this->Cell(1);
        // Arial 8
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(18, 10, 'Telefono:  ', 0, 0, 'L');
        // Arial bold 8
        $this->SetFont('Arial', '', 10);
        $this->Cell(30, 10, $datos['telefono'], 0, 0, 'L');
        // Salto de línea
        $this->Ln(12);


        // DATOS DEL PROYECTO
        $this->SetFont('Arial', 'B', 8);
        $w = $this->GetStringWidth('DATOS DEL PROYECTO') + 155;
        $this->SetX((210 - $w) / 2);
        $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(0, 176, 80);
        $this->SetTextColor(225, 225, 225);
        $this->SetLineWidth(0);
        $this->Cell($w + 1, 5, 'DATOS DEL PROYECTO', 1, 1, 'C', true);
        // Guardar ordenada
        $this->y0 = $this->GetY();
        $this->Ln(0);


        // PROYECTO
        $this->Cell(1);
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->SetLineWidth(0);
        $this->Multicell(189, 10, utf8_decode($datosC['nombre']), 1, 0, 'C');

        $this->Cell(1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 6, 'PLANTA: ', 1, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(80, 6, utf8_decode($datosC['planta']), 1, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 6, 'SITIO: ', 1, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(69, 6, utf8_decode($datosC['direccion']), 1, 0, 'L');
        $this->Ln(6);
        $this->Cell(1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 6, 'CONTRATO: ', 1, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->Cell(169, 6, utf8_decode($datosC['contrato']), 1, 0, 'L');

        // Salto de línea
        $this->Ln(8);


        $this->Cell(1);
        // Tabla de datos
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(0, 176, 80);
        $this->SetTextColor(225, 225, 225);
        $this->Cell(8, 6, 'ID', 1, 0, 'C', 1);
        $this->Cell(10, 6, 'CANT.', 1, 0, 'C', 1);
        $this->Cell(13, 6, 'UNIDAD', 1, 0, 'C', 1);
        $this->Cell(22, 6, 'CODIGO', 1, 0, 'C', 1);
        $this->Cell(83, 6, 'DESCRIPCION', 1, 0, 'C', 1);
        $this->Cell(12, 6, 'TIPO', 1, 0, 'C', 1);
        $this->Cell(41, 6, 'COMENTARIOS', 1, 0, 'C', 1);
        // Salto de línea
        $this->Ln(6);
    }



    // Pie de página
    function Footer()
    {
        global $datos_venta;
        // Posición: a 1,5 cm del final
        $this->SetY(-45);
        
        // Para los datos y firmas
        $this->Cell(1);
        // Tabla de datos
        $this->SetFont('Arial', 'B', 8);
        $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(0, 176, 80);
        $this->SetTextColor(225, 225, 225);
        $this->Cell(95, 6, '                    E M I S O R', 0, 0, 'L', 1);
        $this->Cell(94, 6, '                    R E C E P T O R', 0, 0, 'L', 1);
        // Salto de línea
        $this->Ln(6);
        
        $this->Cell(1);
        $this->SetFont('Arial', 'B', 8);
        // $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 6, '          NOMBRE: ', 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(8, 6, $datos_venta['titulo'], 0, 0, 'L', 1);
        $this->Cell(57, 6, $datos_venta['nombre'], 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(94, 6, '          NOMBRE: ', 0, 0, 'L', 1);
        // Salto de línea
        $this->Ln(6);

        $this->Cell(1);
        $this->SetFont('Arial', 'B', 8);
        // $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(30, 6, '          TELEFONO: ', 0, 0, 'L', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(65, 6, $datos_venta['telefono'], 0, 0, 'L', 1);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(94, 6, '          TELEFONO: ', 0, 0, 'L', 1);
        // Salto de línea
        $this->Ln(18);

        $this->Cell(1);
        $this->SetFont('Arial', 'B', 8);
        // $this->SetDrawColor(59, 78, 136);
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(95, 6, '          FIRMA: _______________________________________ ', 0, 0, 'L', 1);
        $this->Cell(94, 6, '          FIRMA: _______________________________________ ', 0, 0, 'L', 1);
        // Salto de línea
        $this->Ln(5);

        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$contador = 1;
while ($row = $ventas->fetch_assoc()) {

    if ($row['tipo'] == 1) {
        $tipo = '../../assets/img/other.png';
    } else if ($row['tipo'] == 2) {
        $tipo = '../../assets/img/recycle.png';
    } else if ($row['tipo'] == 3) {
        $tipo = '../../assets/img/arrow.png';
    }

    $pdf->Ln(2);
    $pdf->Cell(1);
    $pdf->SetFont('Arial', '', 7);
    $pdf->SetDrawColor(59, 78, 136);

    $pdf->Cell(8, 3, $contador, 0, 0, 'L');

    $y = $pdf->getY();
    $x = $pdf->GetX();
    $max_y = $y;
    $pdf->multiCell(10, 3, utf8_decode($row['cantidad']), 0, 'C');
    $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
    $pdf->SetY($y); // regresar a fila anterior
    $pdf->SetX($x + 10); // regresar a columna anterior mas espacio de la columna

    $y = $pdf->getY();
    $x = $pdf->GetX();
    $pdf->multiCell(13, 3, utf8_decode($row['unidad_medida']), 0, 0);
    $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
    $pdf->SetY($y); // regresar a fila anterior
    $pdf->SetX($x + 13); // regresar a columna anterior mas espacio de la columna

    $y = $pdf->getY();
    $x = $pdf->GetX();
    $pdf->multiCell(22, 3, utf8_decode($row['codigo']), 0, 0);
    $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
    $pdf->SetY($y); // regresar a fila anterior
    $pdf->SetX($x + 22); // regresar a columna anterior mas espacio de la columna

    $y = $pdf->getY();
    $x = $pdf->GetX();
    $pdf->multiCell(83, 3, utf8_decode($row['descripcion']), 0, 0);
    $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
    $pdf->SetY($y); // regresar a fila anterior
    $pdf->SetX($x + 83); // regresar a columna anterior mas espacio de la columna

    $y = $pdf->getY();
    $x = $pdf->GetX();
    $pdf->multiCell(12, 3, $pdf->Image($tipo, $pdf->GetX() + 3, $pdf->GetY() + 1, 5), 0, 0);
    $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
    $pdf->SetY($y); // regresar a fila anterior
    $pdf->SetX($x + 12); // regresar a columna anterior mas espacio de la columna

    $y = $pdf->getY();
    $x = $pdf->GetX();
    $pdf->multiCell(41, 3, '____________________________', 0, 0);
    $max_y = $pdf->getY() > $y ? $pdf->getY() : $y;
    $pdf->SetY($max_y + 4); // regresar a la posicion Y mas grande mas el alto de la fila
    $pdf->SetX($x + 41); // regresar a columna anterior mas espacio de la columna


    $contador++;

    $pdf->Ln();
}

$pdf->Output();
