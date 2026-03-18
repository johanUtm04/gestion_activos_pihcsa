<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { size: 2in 1in; margin: 0; }
    body { 
        margin: 0; padding: 0; 
        width: 2in; height: 1in; 
        font-family: Arial, sans-serif;
        display: flex; justify-content: center; align-items: center;
        background-color: white;
    }

    .etiqueta {
        width: 1.85in; /* Margen de seguridad lateral */
        height: 0.85in; /* Margen de seguridad vertical */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1mm;
        box-sizing: border-box;
    }

    .header { display: flex; align-items: center; width: 100%; margin-bottom: 1mm; }
    .logo { width: 7mm; margin-right: 2mm; }
    .logo img { width: 100%; display: block; }
    .titulo { font-size: 7.5pt; font-weight: bold; }

    .barcode-box {
        width: 100%;
        text-align: center;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    /* LA CLAVE: La imagen no se pixela como los divs */
    .barcode-img {
        max-width: 95%; /* Evita que choque con los bordes */
        height: 28px;   /* Altura fija para que no se deforme */
        display: block;
        margin: 0 auto;
    }

    .serial {
        font-size: 7pt;
        font-family: 'Courier New', monospace;
        font-weight: bold;
        margin-top: 1mm;
        text-align: center;
        letter-spacing: 0.5pt;
    }
</style>
</head>
<body onload="window.print()">
    <div class="etiqueta">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}">
            </div>
            <div class="titulo">ACTIVO FIJO</div>
        </div>

        <div class="barcode-box">
            <img class="barcode-img" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($equipo->serial, 'C128', 2, 40) }}" alt="barcode" />
            
            <div class="serial">
                {{ $equipo->serial }}
            </div>
        </div>
    </div>
</body>
</html>