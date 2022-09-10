<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->customer_name }}<br/>
        {{ $data['master']->customer_address }}<br/>
        </td>
        <td width="300" align="left">
        </td>
        <td width="120">
            <br/>
            Bill No:<br/>
            Bill Date:<br/>
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
            <td width="60" align="center">Sales<br/>Order</td>
            <td width="45" align="center">GMT Part</td>
            <td width="185" align="center">Fabric Description</td>
            <td width="105" align="center">Batch Color &<br/>Batch No</td>
            <td width="45" align="center">GSM/<br/>Weight</td>
            <td width="40" align="center">Dia/<br/>Width</td>
            <td width="45" align="center">Measure<br/>-ment</td>
            <td width="40" align="center">Roll <br/>Length</td>
            <td width="40" align="center">Stitch <br/>Length</td>
            <td width="40" align="center">Shrink %</td>
            <td width="50" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="50" align="center">Rate</td>
            <td width="60" align="center">Amount<br/>{{$data['master']->currency_code}}</td>
            <td width="30" align="center">No of Roll</td>
            <td width="70" align="center">Style & Order</td>
        </tr>
    </thead>
    <tbody>
        <?php
            $i=1;
            $tAmount=0;
        ?>
        @foreach($data['details'] as $row)
        <tr nobr="true">
            <td width="20" align="center">{{$i}}</td>
            <td width="60" align="left">{{ $row->dye_sale_order_no }}</td>
            <td width="45" align="left">{{$row->body_part}}</td>
            <td width="185" align="left">{{$row->fabrication}},{{$row->fabric_shape}},{{$row->fabric_look}}</td>
            <td width="105" align="center">{{$row->batch_color_name}} & {{$row->batch_no}}</td>
            <td width="45" align="center">{{$row->gsm_weight}}</td>
            <td width="40" align="center">{{$row->dia_width}}</td>
            <td width="45" align="center">{{$row->measurement}}</td>
            <td width="40" align="center">{{$row->roll_length}}</td>
            <td width="40" align="center">{{$row->stitch_length}}</td>
            <td width="40" align="center">{{$row->shrink_per}}</td>
            <td width="50" align="right">{{number_format($row->qty,2)}}</td>
            <td width="30" align="center">KG</td>
            <td width="50" align="right">{{number_format($row->rate,2)}}</td>
            <td width="60" align="right">{{number_format($row->qty*$row->rate,2)}}</td>
            <td width="30" align="right">{{$row->number_of_roll}}</td>
            <td width="70" align="center">{{$row->style_ref}} & {{$row->sale_order_no}}</td>
        </tr>
        <?php
            $i++;
            $amount=$row->qty*$row->rate;
            $tAmount+=$amount;
        ?>
        @endforeach
        <tr>
            <td width="665" align="right">Total</td>
            <td width="50" align="right">{{number_format($data['details']->sum('qty'),2)}}</td>
            <td width="30" align="center"></td>
            <td width="50" align="right"></td>
            <td width="60" align="right">{{number_format($tAmount,2)}}</td>
            <td width="30" align="right">{{$data['details']->sum('number_of_roll')}}</td>
            <td width="70" align="center"></td>
        </tr>
    </tbody>
</table>
<p><strong>In Words:{{ $data['master']->inword }}</strong></p>
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