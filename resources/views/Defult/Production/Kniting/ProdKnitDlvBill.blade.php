<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="350">To,
        <br/>
        {{ $data['master']->company_name }}<br/>
        </td>
        <td width="300" align="left">
        </td>
        <td width="120">
            <br/>
            Bill No:<br/>
            Delivery Date:
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->dlv_no }}<br/>
            {{ $data['master']->dlv_date }}
        </td>
    </tr>
    <tr>
        <td width="910">{{ $data['master']->remarks }}</td>
    </tr>
</table>
<?php
    $i=1;
?>
 @foreach($data['details'] as $currency_id=>$items)
 <p>{{ $currency[$currency_id] }}</p>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="70" align="center">GMT Part</td>
            <td width="220" align="center">Fabric Description</td>
            <td width="65" align="center">GSM/Weight</td>
            <td width="50" align="center">Dia/Width</td>
            <td width="65" align="center">Measurement</td>
            <td width="60" align="center">Stitch Length</td>
            <td width="60" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="66" align="center">Pcs</td>
            <td width="60" align="center">Rate</td>
            <td width="60" align="center">Amount</td>
            <td width="40" align="center">Number Of Roll</td>
            
        </tr>
    </thead>
    <tbody>
        <?php
            $i=1;
            $tAmount=0;
            $tQty=0;
            $tRate=0;
            $tPcsQty=0;
            $tNoOfRoll=0;
        ?>
        @foreach($items as $row)
        <tr>
            <td width="30" align="center">{{$i}}</td>
            <td width="70" align="left">{{$row->body_part}}</td>
            <td width="220" align="left">{{$row->fabrication}},{{$row->fabric_shape}},{{$row->fabric_look}}</td>
            <td width="65" align="center">{{$row->gsm_weight}}</td>
            <td width="50" align="center">{{$row->dia_width}}</td>
            <td width="65" align="center">{{$row->measurement}}</td>
            <td width="60" align="center">{{$row->stitch_length}}</td>
            <td width="60" align="right">{{ number_format($row->qty,2) }}</td>
            <td width="30" align="center">KG</td>
            <td width="66" align="right">{{ number_format($row->qty_pcs,0) }}</td>
            <td width="60" align="right">{{ number_format($row->rate,4) }}</td>
            <td width="60" align="right">{{ $row->amount }}</td>
            <td width="40" align="right">{{ $row->number_of_roll }}</td>
            
        </tr>
        <?php
            $i++;
            $tAmount+=$row->amount;
            $tPcsQty+=$row->qty_pcs;
            $tNoOfRoll+=$row->number_of_roll;
        ?>
        @endforeach
        <tr>
            <td width="560" align="right">Total</td>
            <td width="60" align="right">{{ number_format($items->sum('qty'),2) }}</td>
            <td width="30" align="center"></td>
            <td width="66" align="right">{{number_format($tPcsQty,0)}}</td>
            <td width="60" align="right"></td>
            <td width="60" align="right">{{ number_format($tAmount,2) }}</td>
            <td width="40" align="right">{{$tNoOfRoll}}</td>
            
        </tr>
    </tbody>
</table>
<p>{{ App\Library\Numbertowords::ntow(number_format($tAmount,2,'.',''),$currency[$currency_id],$symbol[$currency_id])}}</p>
@endforeach
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