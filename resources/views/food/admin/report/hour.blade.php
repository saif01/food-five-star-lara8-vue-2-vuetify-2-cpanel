<table>
    <thead>
        <tr>
            <td colspan="3" rowspan="3">
                C.P. Bangladesh Co., Ltd. <br> CP Food Outlet Report <br> {{$data->zoneName}} at {{ $label }}{{ $data->date }}
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Name</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Type</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Quantity (piece)</td>
    </thead>

    @foreach($data->finalData as $key => $rawdata)
    <tbody>
        @if($rawdata)
            @foreach($rawdata as $data)
                @if($key == $label)
                    <tr>

                        <td>{{$data['foods']['name_en'] ?? 'N/A' }}</td>
                        <td>{{$data['foods']['type']['name_en'] ?? 'N/A' }}</td>
                        <td>{{$data['total_sales'] ?? 'N/A' }}</td>

                    </tr>
                @endif
            @endforeach
        @endif
        
    </tbody>
    @endforeach
</table>


