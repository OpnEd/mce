<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de Compra No. {{ $order->id }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pdf/orderspdf.css') }}">
    <style>
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <!-- Spanning the first column across three rows -->
                <td rowspan="2"><img src="{{ asset('images/logo.png') }}" alt="Droguería {{ config('app.name') }}"></td>
                <td class="titulo-celda">
                    <h2 class="titulo-celda">Orden de Compra</h2>
                </td>
                <td>
                    <p>OC. No.: <b>{{ '000'.$order->id }}</b></p>
                </td>
            </tr>
            <tr>
                <td><p>Fecha: <b>{{ $formattedDate }}</b></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="content">
        <table>
            <tr>
                <td id="tdClientProveedor">
                    <h2>{{ $company->name }}</h2>
                    <p>Dirección: <b>{{ $company->address }}</b></p>
                    <p>Teléfono: <b>{{ $company->phone_number }}</b></p>
                    <p>Email: <b>{{ $company->email }}</b></p>
                </td>
                <td id="tdClientProveedor">
                    <h3>Enviar al proveedor:</h3>
                    <p>Nombre: <b>{{ $order->proveedor->name }}</b></p>
                    <p>NIT: <b>{{ $order->proveedor->nit . '-' . $order->proveedor->digit }}</b></p>
                    <p>Teléfono: <b>{{ $order->proveedor->phone_number }}</b></p>
                    <p>Email: <b>{{ $order->proveedor->email }}</b></p>
                </td>
            </tr>
        </table>
        <!-- Sección de la tabla de productos -->
        <section>
            <h2>Productos</h2>
            <table>
                <thead>
                    <tr>
                        <th id="thprod">Item</th>
                        <th id="thprod">Código</th>
                        <th id="thprod">Producto</th>
                        <th id="thprod">Cantidad</th>
                        <th id="thprod">Costo Und</th>
                        <th id="thprod">IVA</th>
                        <th id="thprod">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr>
                            <td id="tdprod">{{ $index + 1 }}</td>
                            <td id="tdprod">{{ $codigosProducto[$item['producto']] }}</td>
                            <td id="tdprod">{{ $item['producto'] }}</td>
                            <td id="tdprod">{{ $item['quantity'] }}</td>
                            <td id="tdprod">$ {{ number_format($precios[$item['producto']], 2, '.', ',') }}</td>
                            <td id="tdprod">% {{ number_format($ivas[$item['producto']], 2, '.', ',') }}</td>
                            <td id="tdprod">$ {{ number_format(($item['quantity'] * $precios[$item['producto']] * ( 1 + $ivas[$item['producto']]) ), 2, '.', ',') }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" id="tdprod">Subtotal</td>
                            <td id="tdprod">$ {{ number_format($total_ivas, 2, '.', ',') }}</td>
                            <td id="tdprod">$ {{ number_format($total_totales, 2, '.', ',') }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" id="tdprod">Total</td>
                        <td id="tdprod">$ {{ number_format(($total_ivas + $total_totales), 2, '.', ',') }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- Sección de observaciones -->
        <section>
            <h2>Observaciones</h2>
            <p>{{ $observations }}</p>
        </section>
    </div>
    <div class="footer">
        <div class="textFooter">
            @if ($company != null)
                {{ 'Droguería '. config('app.name') . ' | ' . $company->address . ' | ' . $company->email }}
            @else
                <p>Servicio a la comunidad...!</p>
            @endif
        </div>
    </div>

</body>

</html>
