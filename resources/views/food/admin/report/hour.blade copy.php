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

    @foreach($data->finalData as $rawdata)
    <tbody>
        @if($rawdata)
            @foreach($rawdata as $key => $data2)
                @if($key == $label)
                    @foreach($data2 as $data)
                    <tr>

                        <td>{{$data['foods_name'] ?? 'N/A' }}</td>
                        <td>{{$data['type_name'] ?? 'N/A' }}</td>
                        <td>{{$data['quantity'] ?? 'N/A' }}</td>

                    </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
        
    </tbody>
    @endforeach
</table>


