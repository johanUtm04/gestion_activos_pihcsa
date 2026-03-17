<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: 2in 1in;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 2in;
            height: 1in;
            background-color: white;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .etiqueta-container {
            width: 2in;
            height: 1in;
            padding: 1mm 2mm; /* Pequeño margen interno para que no toque los bordes */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 1mm;
        }

        .logo-box {
            width: 10mm; /* Logo reducido para dar espacio al texto */
            text-align: center;
            margin-right: 2mm;
        }

        .logo-img {
            width: 100%;
            height: auto;
        }

        .titulo {
            font-size: 13pt; /* Tamaño ajustado para 1 pulgada de alto */
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            white-space: nowrap;
        }

        .barcode-section {
            text-align: center;
            width: 100%;
            margin-bottom: 1mm;
        }

        .serial-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 8pt; /* Fuente más pequeña para que el serial largo no se amontone */
            font-weight: bold;
            margin-top: 0.5mm;
            letter-spacing: 0.5px; 
        }

        /* Forzamos que los colores se impriman siempre */
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body onload="window.print();">

    <div class="etiqueta-container">
        <div class="header">
            <div class="logo-box">
                <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}" class="logo-img">
                <div style="font-size: 4pt; font-weight: bold; line-height: 1;">MATERIAL MÉDICO</div>
            </div>
            <h1 class="titulo">Activo Fijo</h1>
        </div>

        <div class="barcode-section">
            <div style="display: inline-block; width: 100%;">
                {{-- Bajamos la escala de 1.8 a 1.1 para que el código sea más denso y quepa en el ancho --}}
                {!! DNS1D::getBarcodeHTML($equipo->serial, 'C128', 1.1, 30) !!} 
            </div>
            <div class="serial-text">{{ $equipo->serial }}</div>
        </div>
    </div>

</body>
</html>