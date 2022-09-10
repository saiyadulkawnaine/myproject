<table border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,
        <br/>
            {{ $data['master']->customer_name }}<br/>
            {{ $data['master']->customer_address }}
        </td>
        <td width="150" align="left">
            
        </td>
        <td width="320">
            <br/>
            <strong>Driver&nbsp;:</strong>&nbsp;{{ $data['master']->driver_name }}, {{ $data['master']->driver_contact_no }}<br/>
            <strong>License No&nbsp;:</strong>&nbsp;{{ $data['master']->driver_license_no }}<br/>
            <strong>Lock No&nbsp;:</strong>&nbsp;{{ $data['master']->lock_no }}<br/>
            <strong>Truck No&nbsp;:</strong>&nbsp;{{ $data['master']->truck_no }}<br/>
            <strong>Recepient&nbsp;:</strong>&nbsp;{{ $data['master']->recipient }}
        </td>
        <td width="190">
            <br/>
            <strong>Challan No&nbsp;:</strong>&nbsp;{{ $data['master']->id }}<br/>
            <strong>Challan Date&nbsp;:</strong>&nbsp;{{ $data['master']->return_date }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910"><br/><strong>Remarks&nbsp;:&nbsp;{{ $data['master']->master_remark }}</strong></td>
    </tr>
</table>
<br/>

<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">SL</td>
            <td width="100" align="center">Sales Order</td>
            <td width="200" align="center">Fabric Desc.</td>
            <td width="60" align="center">Fab.Look</td>
            <td width="60" align="center">Fab.Shape</td>
            <td width="60" align="center">GSM</td>
            <td width="70" align="center">Dyeing Color</td>
            <td width="70" align="center">Color Range</td>
            <td width="40" align="center">UOM</td>
            <td width="70" align="center">Return Qty</td>
            <td width="55" align="center">No of Roll</td>
            <td width="150" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $i=1;
            $totalQty=0;
        ?>
        @foreach($data['details'] as $row)      
        <tr>
            <td width="30" align="center">{{$i}}</td>
            <td width="100" align="center">{{$row->sale_order_no}}</td>
            <td width="200" align="left">{{$row->fabrication}}</td>
            <td width="60" align="center">{{$row->fabriclooks}}</td>
            <td width="60" align="center">{{$row->fabricshape}}</td>
            <td width="60" align="center">{{$row->gmtspart}}</td>
            <td width="70" align="center">{{$row->fabric_color}}</td>
            <td width="70" align="center">{{$row->colorrange_id}}</td>
            <td width="40" align="center">{{$row->uom_code}}</td>
            <td width="70" align="right">{{number_format($row->qty,2)}}</td>
            <td width="55" align="center">{{$row->no_of_roll}}</td>
            <td width="150" align="center">{{$row->remarks}}</td>
        </tr>
        <?php
            $i++;
            $totalQty+=$row->qty;
        ?>
        @endforeach
        <tr>
            <td width="690" align="right"><strong>Total</strong></td>
            <td width="70" align="right"><strong>{{number_format($totalQty,2)}}</strong></td>
            <td width="205" align="center"></td>
        </tr>
    </tbody>
</table>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<table>
    <tr align="center">
        <td width="182">
            Received By
        </td>
        <td width="182">
            Returned By
        </td>
        <td width="182">
            Checked By
        </td>
        <td width="182">
            Department Head
        </td>
        <td width="182">
            Authorised By
        </td>
    </tr>
    <tr align="center">
        <td width="182">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="182">
        </td>
        <td width="182">
        </td>
        <td width="182">
        </td>
        <td width="182">
        </td>
        
    </tr>
</table>