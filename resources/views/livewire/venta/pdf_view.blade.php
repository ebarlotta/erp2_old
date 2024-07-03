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
    @if ($operacion == 'deuda') <div class="text-center"><label>Resumen de Deudas</label></div>
    @else <div class="text-center"><label>Resúmen de Créditos</label></div>
    @endif
    <table class="container col-8">
        <tr class="bg-secondary text-white fw-bold border">
            <td class="border text-center">Nombre</td>
            @if ($operacion == 'deuda') {
                <td class="border text-center">Deuda</td> }
            @else {
                <td class="border text-center">Crédito</td> }
            @endif
        </tr>
        @foreach ($registros as $registro) {
            @if ($operacion == 'deuda') {
                @if ($registro->Saldo > 1) {
                    <tr>
                        <td class="border text-end  mr-3 pr-3">{{ $registro->name }}</td>
                        <td class="border text-end mr-3 pr-3">{{ number_format($registro->Saldo, 2, ',', '.') }}</td> }
                    </tr>
                @endif }
            @else {
                @if ($registro->Saldo < 1) {
                    <tr>
                        <td class="border text-end mr-3 pr-3">{{ $registro->name }}</td>
                        <td class="border text-end mr-3 pr-3">{{ number_format($registro->Saldo * -1, 2, ',', '.') }}</td> }
                    </tr>
                @endif
            @endif }
        @endforeach
        <tr class="bg-secondary">
            <td class="colspan-2 text-end border text-white fw-bold">Total {{ $operacion }} a Compradores</td>
            <td class="border text-end text-white fw-bold">
                Total {{ number_format($saldo, 2, ',', '.') }}
            </td>
        </tr>
    </table>

</body>

</html>
