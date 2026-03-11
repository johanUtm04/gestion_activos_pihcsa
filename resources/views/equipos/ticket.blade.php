<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: 80mm 45mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 80mm;
            height: 45mm;
            background-color: white;
            font-family: Arial, sans-serif;
            overflow: hidden; 
        }

        .etiqueta-container {
            width: 90mm;
            height: 45mm;
            padding: 5mm 5mm; 
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
            margin-bottom: 2mm;
        }

        .logo-box {
            width: 16mm;
            text-align: center;
            margin-right: 4mm;
        }

        .logo-img {
            width: 100%;
            height: auto;
        }

        .titulo {
            font-size: 19pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            white-space: nowrap; 
        }

        .barcode-section {
            text-align: center;
            width: 100%;
            margin-top: auto; 
            margin-bottom: 1mm;
        }

        .serial-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11.5pt; 
            font-weight: bold;
            margin-top: 1.5mm;
            letter-spacing: 2.5px; 
        }

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
                <div style="font-size: 5pt; font-weight: bold; margin-top: 1mm;">MATERIAL MÉDICO</div>
            </div>
            <h1 class="titulo">Activo Fijo</h1>
        </div>

        <div class="barcode-section">
            <div style="display: inline-block;">
                {!! DNS1D::getBarcodeHTML($equipo->serial, 'C128', 1.8, 36) !!} {{-- Altura ajustada a 36 --}}
            </div>
            <div class="serial-text">{{ $equipo->serial }}</div>
        </div>
    </div>

</body>
</html>