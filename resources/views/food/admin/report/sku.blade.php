<table>
    <thead>
        <tr>
            <td colspan="6" rowspan="4">
                C.P. Bangladesh Co., Ltd. <br> CP Five Star SKU Report <br> {{$data->totalQuantity}} {{$data->zoneName}} {{ $data->date }} <br> Total Weight: {{ $data->totalWeight/1000 }} kg || Total Price: {{ $data->totalPrice }} BDT
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Code</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Name</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Product Type</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Quantity (Pack)</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Weight (kg)</td>
            <td style="font-weight:bold; background-color: #3F51B5;color: white">Amount (BDT)</td>
    </thead>
    <tbody>

       

        @foreach ($data->allData as $allData)
            <tr>
                <td> {{ $allData['product_code'] ?? 'N/A' }} </td>
                <td> {{ $allData['product_name'] ?? 'N/A' }} </td>
                <td> {{ $allData['product_type'] ?? 'N/A' }} </td>
                <td> {{ $allData['quantity'] ?? 'N/A' }} </td>
                <td> {{ $allData['weight'] / 1000 ?? 'N/A' }} </td>
                <td> {{ $allData['price'] ?? 'N/A' }} </td>

            </tr>
        @endforeach

        <tr>
            <td colspan="3" style="text-align: right">
                Subtotal
            </td>
            <td>{{ $data->totalQuantity }} </td>
            <td>{{ $data->totalWeight/1000 }} </td>
            <td>৳ {{ $data->totalPrice }}</td>
        </tr>
        
    </tbody>
</table>


