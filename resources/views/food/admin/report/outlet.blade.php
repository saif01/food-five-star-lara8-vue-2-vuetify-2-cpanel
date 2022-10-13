<table>
    <thead>
        <tr>
            <td colspan="3" rowspan="4">
                C.P. Bangladesh Co., Ltd. <br> CP Food Outlet Report <br> {{$data->outletName}} {{ $data->date }} <br>
                Total Sale: {{ $data->allSaleAmount->total_sale ?? 'N/A' }} BDT
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Name</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Type</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Quantity (piece)</td>
    </thead>
    <tbody>
        
        @foreach($data->allSale as $data)
        <tr>

            @if($data['foods'])
            <td>{{ $data['foods']['name_en'] }}</td>
            @else
            <td style="color:red">N/A</td>
            @endif

            @if($data['foods'])
            <td>{{ $data['foods']['type']['name_en'] }}</td>
            @else
            <td style="color:red">N/A</td>
            @endif

            @if($data['sum'])
            <td>{{ $data['sum'] }}</td>
            @else
            <td style="color:red">N/A</td>
            @endif

        </tr>
        @endforeach
    </tbody>
</table>


