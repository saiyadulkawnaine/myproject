<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->store_name }}<br/>
        
        </td>
        <td width="300" align="left">
        </td>
        <td width="120">
            <br/>
            Receive No:<br/>
            Receive Date:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->receive_no }}<br/>
            {{ $data['master']->receive_date }}<br/>
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
            <td width="30" align="center">#</td>
            <td width="80" align="center">GMT Part</td>
            <td width="120" align="center">Fabric Description</td>
            
            <td width="40" align="center">GSM</td>
            <td width="40" align="center">Dia</td>
            <td width="65" align="center">F. Color</td>
            <td width="60" align="center">Stitch Length</td>
            <td width="60" align="center">M/C & Guage</td>
            <td width="60" align="center">Buyer</td>
            <td width="60" align="center">Style & Order</td>
            <td width="60" align="center">Qty</td>
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
            <td width="30" align="center">{{$i}}</td>
            <td width="80" align="left">{{$row->body_part}}</td>
            <td width="120" align="left">{{$row->fabrication}},{{$row->fabric_shape}},{{$row->fabric_look}}</td>
            
            <td width="40" align="center">{{$row->gsm_weight}}</td>
            <td width="40" align="center">{{$row->dia_width}}</td>
            <td width="65" align="center">{{$row->fab_color_name}}</td>
            <td width="60" align="center">{{$row->stitch_length}}</td>
            <td width="60" align="center">{{$row->machine_no}} & {{$row->machine_gg}} </td>
            <td width="60" align="center">{{$row->buyer_name}}</td>
            <td width="60" align="center">{{$row->style_ref}} & {{$row->sale_order_no}}</td>
            
            
            <td width="60" align="right">{{number_format($row->qty,2)}}</td>
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
            <td width="555" align="right">Total</td>
            <td width="60" align="right"></td>
            <td width="60" align="right">{{number_format($data['details']->sum('qty'),2)}}</td>
            <td width="30" align="center"></td>
            <td width="30" align="right">{{$data['details']->sum('number_of_roll')}}</td>
            <td width="30" align="right">{{number_format($data['details']->sum('qty_pcs'),0)}}</td>
            <td width="" align="right"></td>

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
        
        <td width="182">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ date('d-M-Y',strtotime($data['master']->created_at)) }}
        </td>
        <td width="182"></td>
        <td width="182"></td>
        <td width="182"></td>
        
    </tr>
</table>