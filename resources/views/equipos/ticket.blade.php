<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>

@page{
    size:2in 1in;
    margin:0;
}

body{
    margin:0;
    width:2in;
    height:1in;
    font-family:Arial, sans-serif;
}

.etiqueta{
    width:2in;
    height:1in;
    box-sizing:border-box;
    padding:2mm;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

/* HEADER */

.header{
    display:flex;
    align-items:center;
}

.logo{
    width:9mm;
    margin-right:2mm;
}

.logo img{
    width:100%;
}

.titulo{
    font-size:9pt;
    font-weight:bold;
}

/* BARCODE */

.barcode{
    text-align:center;
}

.barcode svg{
    max-width:100%;
}

.serial{
    font-size:7pt;
    font-family:monospace;
    margin-top:1px;
}

</style>
</head>

<body onload="window.print()">

<div class="etiqueta">

<div class="header">

<div class="logo">
<img src="{{ asset('vendor/adminlte/dist/img/logohd.png') }}">
</div>

<div class="titulo">
ACTIVO FIJO
</div>

</div>

<div class="barcode">

{!! DNS1D::getBarcodeHTML($equipo->serial, 'C128', 0.7, 25) !!}

<div class="serial">
{{ $equipo->serial }}
</div>

</div>

</div>

</body>
</html>