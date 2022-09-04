<?php
$table = '';

if(isset($_POST['titles']) && isset($_POST['dataReport']))
{
    session_start();
    date_default_timezone_set('America/Santo_Domingo');
    $fechaActual=date('d/m/Y');
    $horaActual=date('g:i:s A');
    $usuario=(isset($_SESSION["userNombre"]) && $_SESSION["userNombre"])? $_SESSION["userNombre"] : 'User';
    $direccion='Av. Indepencia #234';
    $sector='La Feria';
    $ciudad='Santo Domingo, DN.';
    $linkPagina='www.agm-devs.com.do';
    $urlImage='logon.JPG';
    $cantidadRegistro='Total Reg.: '.count($_POST['dataReport']);

    $nombreReporte='Reporte de '.$_POST['nombreModulo'];

    $th='';
    $td='';
    foreach($_POST['titles'] as $value)
    {
        $th .= '<th style="text-align:left;" >'.ucfirst($value).'</th>';
    }

    foreach($_POST['dataReport'] as $value)
    {
        $td .= '<tr>';
        foreach($value as $data)
        {
            $td .= '<td style="text-align:left;">'.$data.'</td>';
        }
        $td .= '</tr>';
    }

    $table = '<html>
        <head>
            <title>'.$nombreReporte.'</title>
        </head>
        <body style="font-family: \'Roboto\', sans-serif;font-size: 0.8em;">
            <div style="position:fixed;">
                <div align="center">
                    <img src="'.$urlImage.'" height="125" width="125">
                    <br>
                    <div style="position: relative; top: -25px;">
                        <span>'.$direccion.', '.$sector.'</span>
                        <br>
                        <span>'.$ciudad.'</span>
                        <br>
                        <span>'.$linkPagina.'</span>
                    </div>
                </div>

                <div align="right" style="position: relative; top: -74px;">
                    <span>'.$usuario.'</span>
                    <br>
                    <span>'.$horaActual.'</span>
                    <br>
                    <span>'.$fechaActual.'</span>
                </div>

                <div align="center" style="position: relative; top: -80px;">
                    <h2>'.$nombreReporte.'</h2>
                </div>

                <div align="right" style="position:relative; top:770px;">
                    <b>'.$cantidadRegistro.'</b>
                </div>
            </div>

            <div style="position: relative; top: 185px;">
                <table align="center" >
                    <thead style="border: 1px solid;">
                        <tr>
                        '.$th.'
                        </tr>
                    </thead>
                    <tbody>
                        '.$td.'
                    </tbody>
                </table>
            </div>
        </body>
    </html>';
}

// Jalamos las librerias de dompdf
require_once('dompdf/autoload.inc.php');
use Dompdf\Dompdf;
// Inicializamos dompdf
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->setIsHtml5ParserEnabled(true);
$options->setIsRemoteEnabled(true);

// Le pasamos el html a dompdf
$dompdf->loadHtml($table);
// Colocamos als propiedades de la hoja
$dompdf->setPaper("A4", "portrait");
// Escribimos el html en el PDF
$dompdf->render();
$font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
$dompdf->getCanvas()->page_text(512, 155, "Pag: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));

// Ponemos el PDF en el browser
//Estos dos archivos hacern posible la funcionalidad del la funcion output() en la linea 61
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=".$nombreReporte.".pdf");

//$dompdf->stream('Mia.pdf'); //Este codigo es para hacer que se descargue automaticamente
echo $dompdf->output();//Este codigo hacer que uno pueda ver el pdf en el navegador sin descargar
?>