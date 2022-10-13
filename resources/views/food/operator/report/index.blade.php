<table>
    <thead>
        <tr>
            <td colspan="4" rowspan="4">
                C.P. Bangladesh Co., Ltd. <br> CP Food Product Sale Report <br> {{ $data->date }}, Total Sale: {{ $data->allSaleAmount->total_sale }} BDT <br>
                Shop Details: {{ $data->shop->shop_name ?? '' }} ({{ $data->shop->cv_code ?? '' }}) - {{ $data->shop->shop_address ?? '' }}
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Date</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Name</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Type</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Quantity (piece)</td>
    </thead>
    <tbody>
        
        @foreach($data->allData as $data)
        <tr>

            @if($data)
            <td>{{ date('M d, Y', strtotime($data['created_at'])) }}</td>
            @else
            <td style="color:red">N/A</td>
            @endif

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


