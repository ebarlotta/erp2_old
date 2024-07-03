<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body style="font-family: Arial, Helvetica, sans-serif">
    <table class="container col-12" style="font-size: 12px; line-height: 10px">
        <tr class="fw-bold">
            <td class=" col-4 text-left">Empresa: {{ $empresa->name}}</td>
            <td class="  col-4 text-center"><u>REGISTRO IVA COMPRAS</u></td>
            <td class="  col-4 text-end">Mes: {{ $mes }}</td>
        </tr>
        <tr class="fw-bold ">
            <td class="  col-4 text-left">{{ $empresa->direccion}}</td>
            <td class="  col-4 text-center"></td>
            <td class="  col-4 text-end">Libro Nro</td>
        </tr>
        <tr class="fw-bold ">
            <td class="  col-4 text-left">Cuit:{{ $empresa->cuit}} - IB: {{ $empresa->ib}}</td>
            <td class="  col-4 text-center"></td>
            <td class="  col-4 text-end">Página Nro</td>
        </tr>
        <tr class="fw-bold ">
            <td class="  col-4 text-left">Nro Establecimiento: {{ $empresa->establecimiento}}</td>
            <td class="  col-4 text-center"></td>
            <td class="  col-4 text-end">{{ $mes }} - {{ $anio }}</td>
        </tr>
        <tr class="fw-bold ">
            <td class="  col-4 text-left">Condición IVA: {{ $empresa->actividad1}}</td>
            <td class="  col-4 text-center"></td>
            <td class="  col-4 text-end"></td>
        </tr>


    </table>
    <table class="container col-12 mt-1" style="font-size: 9px; line-height: 10px">
        <tr class="bg-secondary text-white fw-bold border">
            <td class="border text-center">Fecha</td>
            <td class="border text-center">Comprobante</td>
            <td class="border text-center">Vendedor</td>
            <td class="border text-center">Cuit</td>
            <td class="border text-center">Bruto</td>
            <td class="border text-center">Iva</td>
            <td class="border text-center">Exento</td>
            <td class="border text-center">Imp.Interno</td>
            <td class="border text-center">Perc.Iva</td>
            <td class="border text-center">Ret.IB</td>
            <td class="border text-center">Ret.GAN</td>
            <td class="border text-center">Total</td>
        </tr>
        @foreach ($registros as $registro) {
            <tr>
                <td class="border text-center mr-3 pr-3">{{ substr($registro->fecha,8,2) ."-". substr($registro->fecha,5,2) ."-". substr($registro->fecha,0,4) }}</td>
                <td class="border text-end mr-3 pr-3">{{ $registro->comprobante }}</td>
                <td class="border text-end mr-3 pr-3">{{ $registro->name }}</td>
                <td class="border text-center mr-3 pr-3">{{ substr($registro->cuit,0,2) ."-" . substr($registro->cuit,2,8) . "-" . substr($registro->cuit,10,1)}}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->BrutoComp, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->MontoIva, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->ExentoComp, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->ImpInternoComp, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->PercepcionIvaComp, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->RetencionIB, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->RetencionGan, 2, ',', '.') }}</td>
                <td class="border text-end mr-3 pr-3">{{ number_format($registro->NetoComp, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach
        <tr class="bg-secondary">
            <td class="border text-end text-white fw-bold"></td>
            <td class="border text-end text-white fw-bold"></td>
            <td class="border text-end text-white fw-bold"></td>
            <td class="border text-end text-white fw-bold">Total  a Proveedores</td>
            <td class="border text-end text-white fw-bold">{{ number_format($BrutoComp, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($MontoIva, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($ExentoComp, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($ImpInternoComp, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($PercepcionIvaComp, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($RetencionIB, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($RetencionGan, 2, ',', '.')}}</td>
            <td class="border text-end text-white fw-bold">{{ number_format($NetoComp, 2, ',', '.')}}</td>
        </tr>
    </table>

</body>

</html>
