<?php
    session_start();
    if (empty($_SESSION['active'])) {
        header('location: ../');
    }
    ob_start();

   require "../conexion.php";
   $query = mysqli_query($conexion, "SELECT * FROM factura ORDER BY nofactura DESC");
   mysqli_close($conexion);
   $cli = mysqli_num_rows($query);

    date_default_timezone_set('America/Santo_Domingo');
    $fechaActual=date('d/m/Y');
    $horaActual=date('g:i:s A');
    $usuario=(isset($_SESSION["nombre"]) && $_SESSION["nombre"])? $_SESSION["nombre"] : 'USER';
    $direccion='Av. Indepencia #234';
    $sector='La Feria';
    $ciudad='Santo Domingo, DN.';
    $linkPagina='www.agm-devs.com.do';
    $urlImage='';
    $nombreReporte='Reporte de Ventas';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=\, initial-scale=1.0">
    <link rel="shorcut icon" type="image/x-icon" href="/logon.JPG"/>
    <title>Reporte de Ventas</title>
    <!-- Bootstrap CSS-->
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<!-- Custom Font Icons CSS-->
	<link rel="stylesheet" href="css/font.css">
</head>
<body>

    <div>
            <center>
                <div align="center">
                    <img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/AGM_Ventas/logon.JPG" height="125" width="125">
                    <br><br>
                    <div style="position: relative; top: -25px;">
                        <span> <?php echo $direccion.', '.$sector ?></span>
                        <br>
                        <span> <?php echo $ciudad ?> </span>
                        <br>
                        <span> <?php echo $linkPagina ?></span>
                    </div>
                </div>

                <div align="right" style="position: relative; top: -200px;">
                    <span> <?php echo $usuario ?></span>
                    <br>
                    <span> <?php echo $horaActual ?></span>
                    <br>
                    <span><?php echo $fechaActual ?></span>
                </div>

                <div align="center" style="position: relative; top: -80px;">
                    <h2><?php echo $nombreReporte ?></h2>
                </div>
            </center>
            <div style="position: relative; top: -50px;">
                <table class="bordered">
			        <thead">
				        <tr>
                            <th style="width: 25px">Codigo</th>
                            <th style="width: 200px">Fecha</th>
                            <th style="width: 30px">Total</th>
                            <th style="width: 250px">Codigo del Cliente</th>
				        </tr>
			        </thead>
					<tbody>
						<?php
						require "../conexion.php";
						$query = mysqli_query($conexion, "SELECT nofactura, fecha, hora, codcliente, totalfactura, estado FROM factura ORDER BY nofactura DESC");
						mysqli_close($conexion);
						$cli = mysqli_num_rows($query);

						if ($cli > 0) {
							while ($dato = mysqli_fetch_array($query)) {
						?>
								<tr>
									<td style="padding-left:5px;"><?php echo $dato['nofactura']; ?></td>
									<td style="padding-left:38px;"><?php echo $dato['fecha'],' ',$dato['hora']; ?></td>
									<td style="padding-left: 10px;"><?php echo $dato['totalfactura']; ?></td>
                                    <td style="padding-left:120px;"><?php echo $dato['codcliente']; ?></td>
								</tr>
						<?php }
						} ?>
					</tbody>
				</table>
            </div>    
        </div>
</body>
</html>
<?php
    $html = ob_get_clean();

    require_once("./dompdf/autoload.inc.php");
    use Dompdf\Dompdf;
    // Inicializamos dompdf
    $dompdf = new Dompdf();

    $options = $dompdf->getOptions();
    $options->set(array('isRemoteEnabled' => true));
    $dompdf->setOptions($options);

    //llamar el contenido del html al pdf
    $dompdf->loadHtml($html);

    // Colocamos als propiedades de la hoja
    $dompdf->setPaper("letter");
    //$dompdf->setPaper("A4", "portrait");
    $dompdf->set_base_path("./vendor/bootstrap/css/bootstrap.min.css");

    // Escribimos el html en el PDF
    $dompdf->render();
    $dompdf->stream("Reporte_de_Ventas.pdf", array("Attachment" => false));

?>