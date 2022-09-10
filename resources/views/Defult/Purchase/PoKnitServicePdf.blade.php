<h2 align="center" style="font-size:50px">Knitting Work Order</h2>
<table cellspacing="0" cellpadding="2">
<tr>
<td width="748">
To,<br/>
{{ $purOrder['master']->supplier_name }}<br/>
{{ $purOrder['master']->supplier_address }}
</td>
<td width="120">
    <br/>
    WO No:<br/>
    WO Date:<br/>
    Delivery Start:<br/>
    Delivery End:
</td>
<td width="80">
    <br/>
     {{ $purOrder['master']->po_no }}<br/>
     {{ $purOrder['master']->po_date }}<br/>
     {{ $purOrder['master']->delv_start_date }}<br/>
     {{ $purOrder['master']->delv_end_date }}
</td>
</tr>
<tr>
    <td width="948">
        <br/>
        Remarks: {{ $purOrder['master']->remarks}}
    </td>
</tr>
</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="100" align="center">Sales Order</td>
            <td width="70" align="center">Body Part</td>
            <td width="125" align="center">Fabric Description</td>
            <td width="30" align="center">GSM/Wgt</td>
            <td width="50" align="center">Fabric Shape</td>
            <td width="50" align="center">Fabric Looks</td>
            <td width="30" align="center">Fin. Dia</td>
            <td width="50" align="center">Measurment</td>
            <td width="50" align="center">Fabric Color</td>

            <td width="75" align="center">Plan Data</td>
            <td width="100" align="center">Yarn</td>
            <td width="65" align="center">Qty</td>
            <td width="40" align="center">EQV Pcs</td>
            <td width="40" align="center">Rate</td>
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $totQty=0;
        $totPcsQty=0;
        $totAmount=0;
        ?>

        @foreach($purOrder['details'] as $row)
        <?php
        $totQty+=$row->qty;
        $totPcsQty+=$row->pcs_qty;
        $totAmount+=$row->amount;

        //$yarn_compo=isset($purOrder['yarn_array'][$row->id]['composition'])?implode(",",$purOrder['yarn_array'][$row->id]['composition']):null;
        //$yarn_count=isset($purOrder['yarn_array'][$row->id]['count'])?implode(",",$purOrder['yarn_array'][$row->id]['count']):null;
        //$yarn_type=isset($purOrder['yarn_array'][$row->id]['type'])?implode(",",$purOrder['yarn_array'][$row->id]['type']):null;
        $yarn=isset($purOrder['yarn_array'][$row->id]['yarn'])?implode(",",$purOrder['yarn_array'][$row->id]['yarn']):null;
        ?>
         <tr nobr="true">
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="100" align="center">{{$row->sale_order_no}}<br/> {{$row->buyer_name}}</td>
            <td width="70" align="center">{{$row->gmtspart_name}}</td>
            <td width="125" align="center">{{$row->fabric_description}}</td>
            <td width="30" align="center">{{$row->gsm_weight}}</td>
            <td width="50" align="center">{{$row->fabricshape}}</td>
            <td width="50" align="center">{{$row->fabriclooks}}</td>
            <td width="30" align="center">{{$row->dia}}</td>
            <td width="50" align="center">{{$row->measurment}}</td>
            <td width="50" align="center">{{$row->color_name}}</td>
            <td width="75" align="left">
                <br/>
                @if ($row->colorrange_name)
                Colorrange:{{$row->colorrange_name}};
                @endif
                @if ($row->pl_dia)
                M/C Dia:{{$row->pl_dia}};
                @endif
                @if ($row->pl_gsm_weight)
                GSM:{{$row->pl_gsm_weight}};
                @endif
                @if ($row->pl_stitch_length)
                Stitch Length:{{$row->pl_stitch_length}};
                @endif
                @if ($row->pl_spandex_stitch_length)
                Spandex S/L:{{$row->pl_spandex_stitch_length}};
                @endif
                @if ($row->pl_draft_ratio)
                Draft Ratio:{{$row->pl_draft_ratio}};
                @endif
                @if ($row->pl_machine_gg)
                Machine Gauge:{{$row->pl_machine_gg}};
                @endif
            </td>
            <td width="100" align="left">
                <br/>
                {{$yarn}}
            </td>
            
            <td width="65" align="right">{{number_format($row->qty,2)}}</td>
            <td width="40" align="right">{{number_format($row->pcs_qty,0)}}</td>
            <td width="40" align="right">{{number_format($row->rate,4)}}</td>
            <td width="65" align="right">{{number_format($row->amount,2)}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td width="28" align="center"></td>
            <td width="100" align="center">Total</td>
            <td width="70" align="center"></td>
            <td width="125" align="center"></td>
            <td width="30" align="center"></td>
            <td width="50" align="center"></td>
            <td width="50" align="center"></td>
            <td width="30" align="center"></td>
            <td width="50" align="center"></td>
            <td width="50" align="center"></td>
            <td width="75" align="center"></td>
            <td width="100" align="center"></td>
            <td width="65" align="right">{{number_format($totQty,2)}}</td>
            <td width="40" align="right">{{number_format($totPcsQty,0)}}</td>
            <td width="40" align="right"></td>
            <td width="65" align="right">{{number_format($totAmount,2)}}</td>
        </tr>
        
    </tfoot>
</table>
<p><strong>In Words : {{ $purOrder['master']->inword }}.</strong></p>
<table>
    <tr>
    <td width="948"><strong>Terms & Conditions:</strong>
    </td>
    </tr>
    <?php
    $i=1;
    ?>
@foreach($purOrder['purchasetermscondition'] as $terms)
    <tr>
        <td width="48">
            {{$i}}.
        </td>
        <td width="900">
            <strong>{{$terms->term}}</strong>
        </td>
    </tr>
    <?php
    $i++;
    ?>
@endforeach
</table>
<p></p>
<p></p>
@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<table>
    <tr align="center">
        <td width="136"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif</td>
    </tr>
    <tr align="center">
        <td width="136">
            Prepared By
        </td>
        <td width="135">Department Manager</td>
        <td width="135">Planning Head</td>
        <td width="135">Head of Department</td>
        <td width="135">GM Finance & Accounts</td>
        <td width="135">Director/C.O.O</td>
        <td width="135">Approved By</td>
    </tr>
    <tr align="center">
        <td width="136">{{$purOrder['master']->user_name}}</td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135"></td>
        <td width="135">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}</td>
    </tr>
</table>