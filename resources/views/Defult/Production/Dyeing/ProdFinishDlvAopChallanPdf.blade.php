<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->customer_name }}<br/>
        {{ $data['master']->customer_address }}<br/>
        
        </td>
        <td width="320" align="left">{{ $data['master']->driver_name }}<br/>
            Driver Phone:{{ $data['master']->driver_contact_no }}<br/>
            License No:{{ $data['master']->driver_license_no }}<br/>
            Truck No:{{ $data['master']->truck_no }}<br/>
            Lock No:{{ $data['master']->lock_no }}
        </td>
        <td width="100">
            <br/>
            Delivery No:<br/>
            Delivery Date:<br/>
            Buyer:<br/>
            To Store:<br/>
            Delivery Place:
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->dlv_no }}<br/>
            {{ $data['master']->dlv_date }}<br/>
            {{ $data['master']->buyer_name }}<br/>
            {{ $data['master']->store_name }}<br/>
            {{ $data['master']->store_address }}
        </td>
    </tr>
    <tr>
        <td width="910">{{ $data['master']->remarks }}</td>
    </tr>
</table>
<br/>
<?php
    $i=1;
?>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="20" align="center">#</td>
            <td width="60" align="center">Sales<br/> Order</td>
            <td width="40" align="center">GMT <br/>Part</td>
            <td width="165" align="center">Fabric Description</td>
            
            <td width="30" align="center">GSM</td>
            <td width="30" align="center">Dia</td>
            <td width="85" align="center">Roll <br/>Color</td>
            <td width="100" align="center">Batch.Color<br/>Batch.No</td>
            <td width="40" align="center">Stitch Length</td>
            <td width="35" align="center">Shrink %</td>
            {{-- <td width="40" align="center">Fabric<br/> Look</td> --}}
            <td width="70" align="center">Style & Order</td>
            
            <td width="40" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="30" align="center">No Of Roll</td>
            <td width="30" align="center">Pcs</td>
            <td width="" align="center">Y. Lot & Desc</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($data['details'] as $row)
        <tr>
            <td width="20" align="center">{{$i}}</td>
            <td width="60" align="center">{{$row->dye_sale_order_no}}</td>
            <td width="40" align="left">{{$row->body_part}}</td>
            <td width="165" align="left">{{$row->fabrication}}, {{$row->fabric_shape}}, {{$row->fabric_look}}</td>
            
            <td width="30" align="center">{{$row->gsm_weight}}</td>
            <td width="30" align="center">{{$row->dia_width}}</td>
            <td width="85" align="center">{{$row->fab_color_name}}</td>
            <td width="100" align="center">{{$row->batch_color_name}}& {{$row->batch_no}}</td>
            <td width="40" align="center">{{$row->stitch_length}}</td>
            <td width="35" align="center">{{$row->shrink_per}}</td>
            {{-- <td width="40" align="center">{{$row->fabric_look}}</td> --}}
            <td width="70" align="center">{{$row->style_ref}} & {{$row->sale_order_no}}</td>
            
            
            <td width="40" align="right">{{number_format($row->qty,2)}}</td>
            <td width="30" align="center">KG</td>
            <td width="30" align="right">{{$row->number_of_roll}}</td>
            <td width="30" align="right">{{number_format($row->qty_pcs,0)}}</td>
            <td width="" align="center">{{$row->yarn}}</td>

        </tr>
        <?php
        $i++;
        ?>
        @endforeach
        <tr>
            <td width="675" align="right">Total</td>
            <td width="40" align="right">{{number_format($data['details']->sum('qty'),2)}}</td>
            <td width="30" align="center"></td>
            <td width="30" align="right">{{$data['details']->sum('number_of_roll')}}</td>
            <td width="30" align="right">{{number_format($data['details']->sum('qty_pcs'),0)}}</td>
            <td width="" align="right"></td>

        </tr>
    </tbody>
</table>
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