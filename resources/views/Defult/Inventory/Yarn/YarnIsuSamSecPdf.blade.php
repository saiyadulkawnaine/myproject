<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->supplier_name }}<br/>
        {{ $data['master']->supplier_address }}
        
        </td>
        <td width="300" align="left">Driver:&nbsp;{{ $data['master']->driver_name }},&nbsp;{{ $data['master']->driver_contact_no }}<br/>
            License No:&nbsp;{{ $data['master']->driver_license_no }}<br/>
            Truck No:&nbsp;{{ $data['master']->truck_no }}<br/>
            Lock No:&nbsp;{{ $data['master']->lock_no }}<br/>
            Recipient:&nbsp;{{ $data['master']->recipient }}
        </td>
        <td width="120">
            <br/>
            Issue No:<br/>
            Issue Date:<br/>
            Issue Basis:<br/>
            Issue Against:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->issue_no }}<br/>
            {{ $data['master']->issue_date }}<br/>
            {{ $data['master']->isu_basis_id }}<br/>
            {{ $data['master']->isu_against_id }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">{{ $data['master']->master_remarks }}</td>
    </tr>
</table>
<br/>
<?php
    $i=1;
?>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="45" align="center">Count</td>
            <td width="200" align="center">Yarn Description</td>
            <td width="50" align="center">Lot</td>
            <td width="60" align="center">Supplier</td>
            <td width="60" align="center">Buyer</td>
            <td width="60" align="center">Style</td>
            <td width="60" align="center">Sales Order</td>
            <td width="60" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="60" align="center">Returnable<br/>Qty</td>
            <td width="90" align="center">Remarks</td>
            <td width="130" align="center">Sample</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{$i}}</td>
            <td width="45" align="center">{{$row->yarn_count}}</td>
            <td width="200" align="left">{{$row->composition}}, {{$row->yarn_type}},  {{$row->brand}}, {{$row->color_name}}</td>
            <td width="50" align="center">{{$row->lot}}</td>
            <td width="60" align="center">{{$row->supplier_name}}</td>
            <td width="60" align="center">{{$row->buyer_name}}</td>
            <td width="60" align="center">{{$row->style_ref}}</td>
            <td width="60" align="center">{{$row->sale_order_no}}, {{$row->company_code}}</td>
            <td width="60" align="right">{{number_format($row->store_qty,4)}}</td>
            <td width="30" align="center">{{$row->uom_code}}</td>
            <td width="60" align="center">{{number_format($row->returnable_qty,4)}}</td>
            <td width="90" align="center">{{$row->remarks}}</td>
            <td width="130" align="center">{{$row->sample_name}}</td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
        <tr>
            <td width="565" align="right">Total</td>
            <td width="60" align="right">{{number_format($data['details']->sum('store_qty'),4)}}</td>
            <td width="30" align="center"></td>
            <td width="60" align="center"></td>
            <td width="90" align="center"></td>
            <td width="130" align="center"></td>
        </tr>
    </tbody>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>


<table>
    <tr align="center">
        <td width="182">
            Received By
        </td>
        <td width="182">
            Issued By
        </td>
        <td width="182">
            Head Of Department 
        </td>
        <td width="182">
            Checked By
        </td>
        <td width="182">
            Authorized By
        </td>
    </tr>
    <tr align="center">
        <td width="182"></td>
        <td width="182">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ date('d-M-Y',strtotime($data['master']->created_at)) }}
        </td>
        <td width="182"></td>
        <td width="182"></td>
        <td width="182"></td>
        
    </tr>
</table>