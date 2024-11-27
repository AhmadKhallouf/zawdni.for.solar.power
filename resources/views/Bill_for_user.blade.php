<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bill</title>
        <style>
            table {
                width: 80%;
              margin: 0 auto;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h2 style="text-align: center;">Your Bill</h2>
        <h4 style="margin-left:12%;">* Your Name:&nbsp; {{ $cart->users->first_name }}&nbsp;{{ $cart->users->last_name }}</h4>
        <h4 style="margin-left:12%;">* Your Cart Id:&nbsp;{{ $cart->id }}</h4>
        <h4 style="margin-left:12%;">* Type Of System:&nbsp; {{ $cart->type_of_system }}</h4>
        <hr style="width: 50%; margin: 0 auto ; margin-top:2%;"/>
        <h4 style="margin-left:12%;">* Your Loads:</h4>
        <table>
            <tr>
                <th>Load</th>
                <th>Watt</th>
                <th>Operating Voltage</th>
                <th>Operating at night</th>
            </tr>
            @if ($cart->loads->isNotEmpty())
            @foreach ( $cart->loads as $item )
            <tr>
                <td>{{ $item->load }}</td>
                <td>{{ $item->watt }}</td>
                <td> @if($item->pivot->run_at_night == 0) No @else Yes @endif</td>
                <td>@if($item->pivot->operating_voltage == 0) No @else Yes @endif</td>
            </tr>
            @endforeach
            @endif
            @if ($cart->additional_loads->isNotEmpty())
            @foreach ( $cart->additional_loads as $item )
            <tr>
                <td>{{ $item->load }}</td>
                <td>{{ $item->watt }}</td>
                <td> @if($item->run_at_night == 0) No @else Yes @endif</td>
                <td>@if($item->operating_voltage == 0) No @else Yes @endif</td>
            </tr>
            @endforeach
            @endif
        </table>
        <h4 style="margin-left:12%;">* Your Inverters:</h4>
        <table>
            
            <tr>
                <th>Type Of Inverter</th>
                <th>ManeFacture Company</th>
                <th>Model</th>
                <th>Watt</th>
                <th>Quantity</th>
				<th>price for one product $</th>
                <th>total Price $</th>
            </tr>
            @foreach ( $cart->inverters as $item )
            <tr>
                <td>{{ $item->type }}</td>
                <td>{{ $item->manufacture_company }}</td>
                <td>{{ $item->model }}</td>
                <td>{{ $item->watt }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>{{ $item->price }}</td>
				<td>{{ $item->pivot->quantity*$item->price }}</td>
            </tr>
            @endforeach
        </table>
        @if($cart->type_of_system == 'household')
        <h4 style="margin-left:12%;">* Your Batteries:</h4>
        <table>
            <tr>
                <th>Type Of Battery</th>
                <th>ManeFacture Company</th>
                <th>Volt</th>
                <th>Amber</th>
                <th>Quantity</th>
                <th>price for one product $</th>
                <th>total Price $</th>
            </tr>
            @foreach ($cart->batteries as $item )
            <tr>
                <td>{{ $item->type }}</td>
                <td>{{ $item->manufacture_company }}</td>
                <td>{{ $item->volt }}</td>
                <td>{{ $item->ampere }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>{{ $item->price }}</td>
				<td>{{ $item->pivot->quantity*$item->price }}</td>
            </tr>
            @endforeach
        </table>
        @endif
        <h4 style="margin-left:12%;">* Your Panels:</h4>
        <table>
            <tr>
                <th>ManeFacture Company</th>
                <th>Model</th>
                <th>Watt</th>
                <th>Quantity</th>
                <th>price for one product $</th>
                <th>total Price $</th>
            </tr>
            @foreach ( $cart->panels as $item )
            <tr>
                <td>{{ $item->manufacture_company }}</td>
                <td>{{ $item->model }}</td>
                <td>{{ $item->watt }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>{{ $item->price }}</td>
				<td>{{ $item->pivot->quantity*$item->price }}</td>
            </tr>
            @endforeach
        </table>
        <hr style="width: 50%; margin: 0 auto ; margin-top:2%;"/>
        <div style="display: flex; justify-content:space-around;align-items: center;">
            <h4>* Cost For One Base Panel: &nbsp; {{ $supplement_price->base_panel_cost }} $ &nbsp; &nbsp; &nbsp; &nbsp;</h4>
            <h4>* Nubmer Of Your Bases: &nbsp; {{ $cart->number_of_panels }} &nbsp; &nbsp; &nbsp; &nbsp;</h4>
<h4>Total Cost: &nbsp; {{ $supplement_price->base_panel_cost*$cart->number_of_panels }} $</h4>
        </div>
        <div style="display: flex; justify-content:space-around;align-items: center;">
            <h4>* Cost For One Meter Of Cable: &nbsp; {{ $supplement_price->one_meter_of_cables_cost }} $ &nbsp; &nbsp; &nbsp; &nbsp;</h4>
<h4>* Your Distance: &nbsp; {{ $cart->distance_from_panels_to_inverter }} M &nbsp; &nbsp; &nbsp; &nbsp;</h4>
            <h4>Total Cost:&nbsp; {{ $supplement_price->one_meter_of_cables_cost*$cart->distance_from_panels_to_inverter}} $</h4>
                    </div>
                    
                    <h4 style="margin-left:12%;">* Installation Cost:&nbsp;
                        @if ($cart->type_of_system == 'household')
                        {{ $supplement_price->household_installation_cost }} &nbsp; $
                        @elseif ($cart->type_of_system == 'agricultural')
                        {{ $supplement_price->agriculture_installation_cost }} &nbsp; $
                        @elseif ($cart->type_of_system == 'industrial')
                        {{ $supplement_price->industrial_installation_cost }} &nbsp; $
                        @endif
                    </h4>
                    <hr style="width: 50%; margin: 0 auto ; margin-top:2%;"/>
                    <h2 style="text-align: center;">Total Cost In Dollar:&nbsp; {{ $cart->total_price }}  $</h2>
					<h2 style="text-align: center;">Total Cost In Syrian Pound:&nbsp; {{ $cart->total_price*$supplement_price->dollar_price_against_sp}}  s.p</h2>
    </body>
</html>